<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionRule;
use App\Models\Package;
use App\Models\PackageAddon;
use App\Models\PromoterCommission;
use Illuminate\Http\Request;

class CommissionSetupController extends Controller
{
    public function index()
    {
        $authUser = auth()->guard('organizer')
            ->user()
            ->load('user');

        $organizerId = $authUser->id;

        // 1️⃣ Workers (include organizer as selectable worker)
        $workers = \App\Models\Worker::where('organizer_id', $organizerId)
            ->get();

        // Add current organizer as pseudo worker option
        $workers->push((object) [
            'id' => 'organizer_' . $organizerId,
            'name' => $authUser->name ?? 'Organizer',
            'type' => 'organizer'
        ]);

        // 2️⃣ Packages (only from this organizer)
        $packages = \App\Models\Package::where('organizer_id', $organizerId)
            ->where('status', 'active')
            ->get();

        // Get package IDs
        $packageIds = $packages->pluck('id');

        // 3️⃣ Addons (based on those packages)
        $addons = collect();

        // 4️⃣ Commission Rules (scoped to organizer)
        $rules = CommissionRule::with('worker')
            ->where('organizer_id', $organizerId)
            ->get();

        // 5️⃣ Promoter commission (per organizer)
        // $promoter = PromoterCommission::where('organizer_id', $organizerId)
        //     ->first();

        return view('admin.commission.setup', compact(
            'workers',
            'packages',
            'addons',
            'rules',
            // 'promoter',
            'authUser'
        ));
    }

    public function store(Request $request)
    {
        $authUser = auth()->guard('organizer')->user();
        $organizerId = $authUser->id;

        $data = $request->validate([
            'worker_id' => 'required|exists:users,id',
            'package_id' => 'nullable|exists:packages,id',
            'addon_id' => 'nullable|exists:package_addons,id',
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
        ]);

        if ($data['commission_type'] === 'percentage' && $data['commission_value'] > 100) {
            return back()->withErrors(['commission_value' => 'Percentage cannot exceed 100%']);
        }

        // Prevent duplicate within SAME organizer
        $exists = CommissionRule::where('organizer_id', $organizerId)
            ->where('worker_id', $data['worker_id'])
            ->where('package_id', $data['package_id'] ?? null)
            ->where('addon_id', $data['addon_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'duplicate' => 'Commission rule already exists for this worker/package/addon combination'
            ]);
        }

        // Attach organizer_id
        $data['organizer_id'] = $organizerId;

        CommissionRule::create($data);

        return redirect()
            ->route('commission.setup')
            ->with('success','Commission rule created');
    }

    public function update(Request $request, CommissionRule $commissionRule)
    {
        $data = $request->validate([
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
        ]);

        if ($data['commission_type'] === 'percentage' && $data['commission_value'] > 100) {
            return back()->withErrors(['commission_value' => 'Percentage cannot exceed 100%']);
        }

        $commissionRule->update($data);

        return redirect()->route('commission.setup')->with('success','Commission rule updated');
    }

    public function destroy(CommissionRule $commissionRule)
    {
        $commissionRule->delete();
        return redirect()->route('commission.setup')->with('success','Deleted');
    }

    public function savePromoter(Request $request)
    {
        $data = $request->validate([
            'commission_type' => 'required|in:percentage,fixed',
            'commission_value' => 'required|numeric|min:0',
        ]);

        if ($data['commission_type'] === 'percentage' && $data['commission_value'] > 100) {
            return back()->withErrors(['commission_value' => 'Percentage cannot exceed 100%']);
        }

        $promoter = PromoterCommission::first();
        if ($promoter) {
            $promoter->update($data);
        } else {
            PromoterCommission::create($data);
        }
        return redirect()->route('commission.setup')->with('success','Promoter commission saved');
    }

    public function getAddons($packageId)
    {
        $authUser = auth()->guard('organizer')->user();

        $addons = \App\Models\PackageAddon::where('package_id', $packageId)
            ->where('is_active', true)
            ->whereHas('package', function ($q) use ($authUser) {
                $q->where('organizer_id', $authUser->id);
            })
            ->get(['id','name']);

        return response()->json($addons);
    }
}
