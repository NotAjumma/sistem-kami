<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CommissionCalculator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CommissionReportController extends Controller
{
    public function index(Request $request)
    {
        $organizerId = auth()->guard('organizer')->id();

        // 1️⃣ Fetch bookings
        $query = \App\Models\Booking::with(['package','addons','promoter'])
            ->where('organizer_id', $organizerId);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }
        if ($request->filled('worker_id')) {
            $query->whereHas('workers', function($q) use ($request){ $q->where('users.id', $request->input('worker_id')); });
        }
        if ($request->filled('promoter_id')) {
            $query->where('promoter_id', $request->input('promoter_id'));
        }
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->input('package_id'));
        }

        // Apply filters...
        $bookings = $query->get();

        // 2️⃣ PRELOAD workers and commission rules upfront
        $workers = \App\Models\Worker::where('organizer_id', $organizerId)->get();

        // Add current organizer as pseudo worker option
        $workers->push((object) [
            'id' => $organizerId,   // numeric
            'name' => $authUser->name ?? 'Organizer',
            'type' => 'organizer'
        ]);

        $commissionRules = \App\Models\CommissionRule::where('organizer_id', $organizerId)->get();

        // 2️⃣ Initialize per-worker totals AFTER adding organizer
        $workerTotals = $workers->mapWithKeys(fn($w) => [$w->id => 0.0])->toArray();

        $report = [];
        $totalRevenue = 0;
        $totalPromoterCommission = 0;
        $totalCompanyNet = 0;

        foreach ($bookings as $b) {
            $bookingStruct = [
                'id' => $b->id,
                'created_at' => $b->created_at->toDateTimeString(),
                'package_id' => $b->package_id,
                'package_name' => $b->package->name ?? null,
                'amount' => (float) $b->final_price,
                'addons' => $b->addons->map(fn($ba) => [
                    'id' => $ba->id,                         // booking_addon id
                    'addon_id' => $ba->addon_id,             // link to package_addons.id
                    'name' => $ba->name ?? '',
                    'price' => (float)$ba->price,
                ])->toArray(),
                'promoter_id' => $b->promoter_id,
            ];

            // Calculate commissions for all workers
            $calc = \App\Services\CommissionCalculator::calculateForBooking($bookingStruct, $workers, $commissionRules);

            // Add to worker totals
            foreach ($calc['worker_breakdown'] as $wb) {
                $workerTotals[$wb['worker_id']] += $wb['commission'];
            }

            $totalRevenue += $bookingStruct['amount'];
            $totalPromoterCommission += $calc['promoter_commission'];
            $totalCompanyNet += $calc['company_net'];

            $report[] = array_merge($bookingStruct, $calc);
        }

        if ($request->wantsJson() || $request->input('export') == 'json') {
            return response()->json(['report' => $report, 'summary' => $summary]);
        }

        if ($request->input('export') == 'excel') {
            // simple array export - requires maatwebsite/excel installed and configured
            $rows = [];
            foreach ($report as $r) {
                $rows[] = [
                    'Booking ID' => $r['id'],
                    'Date' => $r['created_at'],
                    'Package' => $r['package_name'],
                    'Amount' => $r['amount'],
                    'Total Worker Commission' => $r['total_worker_commission'],
                    'Promoter Commission' => $r['promoter_commission'],
                    'Company Net' => $r['company_net'],
                ];
            }

            return Excel::download(new \Maatwebsite\Excel\Collections\SheetCollection(['Report' => collect($rows)]), 'commission_report.xlsx');
        }

        $authUser = auth()->guard('organizer')
            ->user()
            ->load('user');

        $organizerId = $authUser->id;

        // 1️⃣ Workers (include organizer as selectable worker)
        $workers = \App\Models\Worker::where('organizer_id', $organizerId)
            ->get();

        // Add current organizer as pseudo worker option
        $workers->push((object) [
            'id' => $organizerId,   // numeric
            'name' => $authUser->name ?? 'Organizer',
            'type' => 'organizer'
        ]);
        $packages = \App\Models\Package::where('organizer_id', $organizerId)
            ->where('status', 'active')
            ->get();

        return view('admin.commission.report', compact(
            'report',
            'workers',
            'packages',
            'authUser',
            'workerTotals',
            'totalRevenue',
            'totalPromoterCommission',
            'totalCompanyNet'
        ));
    }
}
