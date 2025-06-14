<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\TicketInput;
use App\Models\Event;
use App\Models\BookingTicket;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Services\ToyyibPayService;

class BookingController extends Controller
{
    public function gFormSync(Request $request)
    {
        $data = $request->all();

        // Hardcode for ticket event strike joran RM100/joran
        $ticket_id = 2;
        DB::beginTransaction();

        try {
            // 0. Simpan atau update participant
            $participant = Participant::updateOrCreate(
                [
                    'email' => $data['email'],
                    'no_ic' => $data['no_ic'],
                ],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'] ?? null,
                ]
            );
            // 1. Cari ticket type (master ticket)
            $ticket = Ticket::with('event')->find($ticket_id);

            if (!$ticket || !$ticket->event) {
                DB::rollBack();
                return response()->json(['error' => 'Invalid ticket or event data'], 400);
            }

            $eventCode = strtoupper($ticket->event->shortcode ?? 'DFT');

            // 2. Buat booking baru
            $resitPath = null;

            if ($request->has('resit_pembayaran_base64')) {
                // $base64 = $request->input('resit_pembayaran_base64');
                $filename = $request->input('resit_filename', 'resit_' . time() . '.jpg');
                // $fileData = base64_decode($base64);

                // // Define local path relative to project root
                // $localPath = public_path('images/receipts/' . $filename);

                // // Pastikan folder wujud
                // if (!file_exists(dirname($localPath))) {
                //     mkdir(dirname($localPath), 0755, true);
                // }

                // // Simpan file
                // file_put_contents($localPath, $fileData);

                // Simpan path relatif untuk database (contoh: images/resit/filename.jpg)
                $resitPath = $filename;
            }
            $bilanganJoran = (int) ($data['bilangan_joran'] ?? 1);
            $totalPrice = (float) $ticket->price * (int) $bilanganJoran;

            $extraInfo = [
                'shirt_size' => $data['shirt_size'] ?? null,
            ];
            // Buat booking baru
            $booking = Booking::create([
                'participant_id' => $participant->id,
                'event_id' => $ticket->event_id,
                'organizer_id' => $ticket->event->organizer_id,
                'booking_code' => $eventCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(6)),
                'status' => 'pending',
                'total_price' => $totalPrice,
                'payment_method' => 'gform',
                'resit_path' => $resitPath ?? null,
                'extra_info' => $extraInfo,
            ]);

            // 4. Buat booking ticket detail
            for ($i = 0; $i < $bilanganJoran; $i++) {
                BookingTicket::create([
                    'booking_id' => $booking->id,
                    'ticket_id' => $ticket->id,
                    'price' => $ticket->price,
                    'participant_name' => $data['participant_name'] ?? $data['name'],
                    'participant_email' => $data['email'],
                    'participant_no_ic' => $data['no_ic'] ?? null,
                    'participant_phone' => $data['phone'] ?? null,
                    // 'ticket_code' => $eventCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(4)),
                    'ticket_code' => $eventCode . '-' . now()->format('His') . '-' . strtoupper(Str::random(6)),
                    'status' => 'pending',
                ]);
            }

            Artisan::call('drive:download-receipts');
            DB::commit();

            return response()->json(['message' => 'Sync successful'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            $data = $request->all();

            // Log to sync_failed table
            $noIc = $data['no_ic'] ?? '';
            $email = $data['email'] ?? '';

            // Format uniq_code by concatenating no_ic and email, separated by underscore or any delimiter you want
            $uniqCode = $noIc . '_' . $email;

            DB::table('sync_failed')->insert([
                'module' => 'booking_sync',
                'uniq_code' => $uniqCode,
                'data' => json_encode($request->all()),
                'error' => $e->getMessage(),
                'created_at' => now()
            ]);

            // $credPath = storage_path('app/credentials.json');

            // if (!file_exists($credPath) || filesize($credPath) === 0) {

            //     DB::table('sync_failed')->insert([
            //         'module' => 'checking_gcred',
            //         'uniq_code' => $uniqCode,
            //         'data' => json_encode($data),
            //         'error' => 'Invalid or empty credentials.json file',
            //         'created_at' => now(),
            //     ]);
            // }


            return response()->json([
                'error' => 'Sync failed',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function validatePayment(Request $request, $id)
    {
        $booking = Booking::with(['participant', 'bookingTickets'])->findOrFail($id);

        // 1. Update booking status
        $booking->status = 'Completed';
        $booking->save();

        // 2. Update all related booking ticket statuses
        foreach ($booking->bookingTickets as $ticket) {
            $ticket->status = 'Completed';
            $ticket->save();
        }

        // 3. Send email to participant
        Mail::to($booking->participant->email)->send(new PaymentConfirmed($booking));

        return redirect()->back()->with('success', 'Payment validated and email sent.');
    }

    // After check select ticket and before checkout
    public function storeSelection(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
            'tickets.*.id' => 'required|exists:tickets,id',
            'tickets.*.quantity' => 'required|integer|min:0',
        ]);

        $eventId = $validated['event_id'];

        // \Log::info($validated['tickets']);
        // Get all ticket IDs from validated tickets that have quantity > 0
        $selectedTicketIds = collect($validated['tickets'])
            ->filter(fn($t) => $t['quantity'] > 0)
            ->pluck('id') // use 'id' here
            ->all();

        $tickets = Ticket::where('event_id', $eventId)
            ->whereIn('id', $selectedTicketIds)
            ->get(['id', 'name', 'price', 'is_active']);

        $selectedTickets = collect($validated['tickets'])
            ->filter(fn($t) => $t['quantity'] > 0)
            ->map(function ($selected) use ($tickets) {
                $ticket = $tickets->firstWhere('id', $selected['id']);
                if (!$ticket) {
                    return null;
                }
                return [
                    'ticket_id' => $ticket->id,
                    'name' => $ticket->name,
                    'price' => $ticket->price,
                    'is_active' => $ticket->is_active,
                    'quantity' => $selected['quantity'],
                ];
            })
            ->filter()  // remove nulls
            ->values();


        if ($selectedTickets->isEmpty()) {
            return back()->with('error', 'Please select at least one ticket.');
        }

        // ✅ Fetch and group ticket_inputs
        $ticketInputs = TicketInput::whereIn('ticket_id', $selectedTicketIds)
            ->orderBy('sort_order')
            ->get()
            ->groupBy(['ticket_id', fn($input) => $input->parent ?? 'General']);

        $ticketInputsArray = $ticketInputs->map(function ($byParent) {
            return $byParent->map(function ($inputs) {
                return $inputs->map(fn($input) => [
                    'id' => $input->id,
                    'label' => $input->label,
                    'input_type' => $input->input_type,
                    'options' => $input->options,
                    'is_required' => $input->is_required,
                    'sort_order' => $input->sort_order,
                    'placeholder' => $input->placeholder,
                ]);
            });
        });


        // Store in session for checkout/payment process
        session([
            'selected_event' => Event::select([
                'id',
                'organizer_id',
                'title',
                'shortcode',
                'start_date',
                'start_time',
                'district',
                'state',
                'country',
                'images',
                'status',
                'registration_deadline',
                'is_free',
                'service_charge_fixed',
                'service_charge_percentage',
            ])
                ->where('id', $validated['event_id'])
                ->first(),
            'selected_tickets' => $selectedTickets,
            'selected_ticket_inputs' => $ticketInputsArray,
        ]);

        return redirect()->route('checkout');
    }

    public function showCheckout()
    {
        $event = session('selected_event');
        $tickets = session('selected_tickets');
        $ticketInputs = session('selected_ticket_inputs');

        \Log::info($event);
        \Log::info($tickets);
        if (!$event || !$tickets) {
            return redirect('/')->with('error', 'Please select tickets first.');
        }

        return view('home.checkout', compact('event', 'tickets', 'ticketInputs'));
    }

    public function webFormBooking(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'no_ic' => 'required|string',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'postcode' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            \Log::info('Web form booking initiated', $request->only(['name', 'email', 'no_ic']));

            $participant = Participant::create($request->only([
                'name',
                'email',
                'no_ic',
                'phone',
                'country',
                'state',
                'city',
                'postcode',
                'address',
            ]));

            $event = session('selected_event');
            $tickets = session('selected_tickets');

            if (!$event || !$tickets) {
                \Log::warning('Session expired or incomplete', ['event' => $event, 'tickets' => $tickets]);
                throw new \Exception('Session expired or incomplete. Sila pilih tiket semula.');
            }

            $eventCode = strtoupper($event['shortcode'] ?? 'DFT');
            $totalPrice = 0;

            $booking = Booking::create([
                'participant_id' => $participant->id,
                'event_id' => $event['id'],
                'organizer_id' => $event['organizer_id'] ?? null,
                'booking_code' => $eventCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(6)),
                'status' => 'pending',
                'total_price' => 0,
                'payment_method' => 'sistemkami-toyyibpay',
                'extra_info' => ['shirt_size' => $request->shirt_size],
            ]);

            foreach ($tickets as $ticket) {
                $quantity = (int) $ticket['quantity'];
                $price = (float) $ticket['price'];

                for ($i = 0; $i < $quantity; $i++) {
                    BookingTicket::create([
                        'booking_id' => $booking->id,
                        'ticket_id' => $ticket['ticket_id'],
                        'price' => $price,
                        'participant_name' => $request->name,
                        'participant_email' => $request->email,
                        'participant_no_ic' => $request->no_ic,
                        'participant_phone' => $request->phone,
                        'ticket_code' => $eventCode . '-' . now()->format('His') . '-' . strtoupper(Str::random(6)),
                        'status' => 'paid',
                    ]);
                }

                $totalPrice += $price * $quantity;
            }

            $fixed = $event['service_charge_fixed'] ?? null;
            $percentage = $event['service_charge_percentage'] ?? null;

            if (!is_null($percentage) && $percentage != 0) {
                $serviceCharge = $totalPrice * ($percentage / 100);
            } elseif (!is_null($fixed) && $fixed != 0) {
                $serviceCharge = $fixed;
            } else {
                $serviceCharge = 0;
            }

            $grandTotal = $totalPrice + $serviceCharge;

            \Log::info('Booking totals calculated', [
                'booking_id' => $booking->id,
                'total_price' => $totalPrice,
                'service_charge' => $serviceCharge,
                'grand_total' => $grandTotal
            ]);

            $booking->update([
                'total_price' => $totalPrice,
                'service_charge' => $serviceCharge
            ]);

            // === ToyyibPay integration ===
            $toyyibPay = new ToyyibPayService();
            $bill = $toyyibPay->createBill([
                'name' => Str::limit('Payment for ' . $event['title'], 30),
                'description' => 'Booked by ' . $participant->name,
                'amount' => $grandTotal,
                'ref_no' => $booking->booking_code,
                'to' => $participant->name,
                'email' => $participant->email,
                'phone' => $participant->phone,
            ]);

            \Log::info('ToyyibPay bill response', ['bill' => $bill]);

            if (!isset($bill[0]['BillCode'])) {
                throw new \Exception('ToyyibPay: BillCode gagal dicipta');
            }

            Payment::create([
                'booking_id' => $booking->id,
                'bill_code' => $bill[0]['BillCode'],
                'ref_no' => $booking->booking_code,
                'amount' => $grandTotal,
                'status' => 'pending',
            ]);

            \Log::info('Payment record created', [
                'booking_id' => $booking->id,
                'bill_code' => $bill[0]['BillCode'],
            ]);

            DB::commit();

            $baseUrl = config('toyyibpay.sandbox') ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
            return redirect()->away($baseUrl . '/' . $bill[0]['BillCode']);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Web form booking failed', [
                'message' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return back()->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
        }
    }

    public function handleCallback(Request $request)
    {
        \Log::info('ToyyibPay callback received:', $request->all());

        $payment = Payment::where('bill_code', $request->billcode)->first();

        if (!$payment) {
            \Log::warning('Callback tanpa payment (invalid bill_code):', $request->all());
            return response()->json(['error' => 'Invalid bill code'], 404);
        }

        $status = $request->status_id == 1 ? 'paid' : 'failed';

        $payment->update([
            'status' => $status,
            'paid_at' => now(),
            'raw_response' => $request->all(),
        ]);

        \Log::info('Payment updated:', [
            'payment_id' => $payment->id,
            'status' => $status,
        ]);

        if ($status === 'paid') {
            $payment->booking->update(['status' => 'paid']);
            \Log::info('Booking marked as paid:', ['booking_id' => $payment->booking->id]);
        } else {
            $payment->booking->update(['status' => 'failed']);
            \Log::warning('Booking marked as failed:', [
                'booking_id' => $payment->booking->id,
                'reason' => $request->description ?? 'No reason given'
            ]);
        }

        return response()->json(['success' => true]);
    }

}