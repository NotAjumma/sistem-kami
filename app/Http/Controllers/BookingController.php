<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookingsVendorTimeSlot;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\TicketInput;
use App\Models\PackageInput;
use App\Models\VendorOffDay;
use App\Models\Event;
use App\Models\VendorTimeSlot;
use App\Models\BookingTicket;
use App\Models\Ticket;
use App\Models\Package;
use App\Models\PackageAddon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use App\Services\ToyyibPayService;
use Carbon\Carbon;
use App\Models\PackageInputAnswer;

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

        // \Log::info($event);
        // \Log::info($tickets);
        if (!$event || !$tickets) {
            return redirect('/')->with('error', 'Please select tickets first.');
        }

        return view('home.checkout_package', compact('event', 'tickets', 'ticketInputs'));
    }

    public function storeSelectionPackage(Request $request)
    {
        $validated = $request->validate([
            'package_id'        => 'required|exists:packages,id',
            'organizer_id'      => 'required|exists:organizers,id',
            'selected_date'     => 'required|date',
            'selected_time'     => 'nullable|string',
        ]);

        // For testing
        // $package_date = "2025-07-04";
        // $packageDate = Carbon::parse( $package_date)->startOfDay();

        // ✅ Check if the selected date is already booked for this package
        $packageDate = Carbon::parse($validated['selected_date'])->startOfDay();
        $today = now()->startOfDay();

        // ✅ 1. Reject if in the past
        if ($packageDate->lt($today)) {
            \Log::info($packageDate . 'The selected date is in the past.');
            return back()->withErrors(['selected_date' => 'The selected date is in the past.']);
        }

        // ✅ 2. Reject if in organizer off days
        $offDays = VendorOffDay::where('organizer_id', $validated['organizer_id'])
            ->whereDate('off_date', '>=', $today)
            ->pluck('off_date')
            ->map(fn($d) => Carbon::parse($d)->format('Y-m-d'))
            ->toArray();

        if (in_array($packageDate->format('Y-m-d'), $offDays)) {
            \Log::info($packageDate . 'The selected date falls on an organizer off day.');
            return back()->withErrors(['selected_date' => 'The selected date falls on an organizer off day.']);
        }

        $timeSlots = VendorTimeSlot::where('organizer_id', $validated['organizer_id'])
            ->where('organizer_id', $validated['package_id'])
            ->where('is_active', 1)
            ->orderBy('start_time')
            ->get();

        if ($timeSlots && $timeSlots->count() > 0) {
            // ✅ 3. Reject if already booked for that package
            $dateTaken = BookingsVendorTimeSlot::where('package_id', $validated['package_id'])
                ->whereDate('booked_date_start', '<=', $packageDate)
                ->whereDate('booked_date_end', '>=', $packageDate)
                ->whereNotIn('status', ['failed', 'pending']) // ✅ Only consider paid/full_payment/deposit.
                ->exists();

            if ($dateTaken) {
                \Log::info($packageDate . 'The selected date is already booked for this package.');
                return back()->withErrors(['selected_date' => 'The selected date is already booked for this package.']);
            }
        }

        // \Log::info($validated['package_id']);
        // \Log::info($validated['selected_date']);

        $package = Package::with([
            'addons',
            'items',
            'discounts',
            'category',
            'images',
            'organizer',
            'vendorTimeSlots',
        ])->findOrFail($validated['package_id']);

        $packageInputs = PackageInput::where('package_id', $validated['package_id'])
            ->orderBy('sort_order')
            ->get()
            ->groupBy(fn($input) => $input->parent ?? 'General');

        $packageInputsArray = $packageInputs->map(function ($inputs) {
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

        // Decode selected times
        $selectedTimes = [];
        if (!empty($validated['selected_time'])) {
            $selectedTimes = json_decode($validated['selected_time'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid selected_time format'], 422);
            }
        }

        // Load package with vendor_time_slots
        $package = Package::with('vendorTimeSlots')->findOrFail($validated['package_id']);

        // Map selected times to include start/end time
        $bookedTimes = collect($selectedTimes)->map(function ($time) use ($package) {
            // Find the matching vendor_time_slot by ID
            $slot = $package->vendorTimeSlots->firstWhere('id', $time['id']);

            if (!$slot) {
                return null; // skip if slot not found
            }

            $start = Carbon::parse($time['time']);
            $end = (clone $start)->addMinutes($slot->slot_duration_minutes);

            return [
                'date'               => $time['date'],
                'court'              => $time['court'] ?? null,
                'slot_id'            => $time['id'],
                'slot_name'          => $slot->name ?? null,
                'booked_time_start'  => $start->format('H:i:s'),
                'booked_time_end'    => $end->format('H:i:s'),
            ];
        })->filter()->values()->all();

        // Store in session
        session([
            'selected_package' => $package,
            'selected_date'    => $validated['selected_date'],
            'selected_time'    => $bookedTimes ?? [],
            'package_inputs'   => $packageInputsArray ?? [],
        ]);

        return redirect()->route('business.checkout_package');
    }

    public function showCheckoutPackage()
    {

        $page_title         = "Checkout Package";
        $package            = session('selected_package');
        $selected_date      = session('selected_date');
        $selected_time      = session('selected_time');
        $package_inputs     = session('package_inputs');

        if (!$package || !$selected_date || !$package_inputs) {
            return back()->withErrors([
                'message' => 'Package selection session has expired. Please select a package again.'
            ]);
        }

        $basePrice      = 0;
        $eachSlotPrice  = 0;
        
        if (!empty($selected_time)){
            $selectedCount  = count($selected_time);
            $basePrice      = $selectedCount * (float) $package['base_price'];
            $eachSlotPrice  = (float) $package['base_price'];
        } else{
            $basePrice = (float) $package['base_price']; // or base_price if needed
        }
        
        $discountAmount = 0;

        // Check if there's an active discount
        if (!empty($package['discounts'])) {
            foreach ($package['discounts'] as $discount) {
                if ($discount['is_active']) {
                    if ($discount['type'] === 'fixed') {
                        $discountAmount = (float) $discount['amount'];
                    } elseif ($discount['type'] === 'percentage') {
                        $discountAmount = $basePrice * ((float) $discount['amount'] / 100);
                    }
                    break; // assuming one active discount at a time
                }
            }
        }

        $subtotal = max(0, $basePrice - $discountAmount); // Ensure non-negative subtotal

        $fixed          = (float) $package['service_charge_fixed'] ?? 0;
        $percentage     = (float) $package['service_charge_percentage'] ?? 0;
        $serviceCharge  = ($percentage > 0 ? $subtotal * ($percentage / 100) : $fixed);

        $total = $subtotal + $serviceCharge;
        
        // calc deposit amount
        $depositFixed       = (float) $package['deposit_fixed'] ?? 0;
        $depositPercentage  = (float) $package['deposit_percentage'] ?? 0;
        $depositAmount      = ($depositPercentage > 0 ? $total * ($depositPercentage / 100) : $depositFixed);

        // Label for UI
        $serviceChargeLabel = 'Service Charge';
        if ($percentage) {
            $serviceChargeLabel .= " (" . $percentage . "%)";
        } elseif ($fixed) {
            $serviceChargeLabel .= " (RM" . number_format($fixed, 2) . ")";
        }

        $depositLabel = '';
        if ($depositPercentage) {
            $depositLabel = " (" .$depositPercentage . "%)";
        } elseif($depositFixed){
            $depositLabel .= " (RM" . number_format($depositFixed, 2) . ")";
        }

        // \Log::info($package);
        // \Log::info("selected_time");
        // \Log::info(print_r($selected_time, true));
        if (!$package || !$selected_date) {
            return redirect()->back()->with('success', 'Please select date first.');
        }

        return view('home.business.checkoutPackage', compact(
            'page_title',
            'package', 
            'selected_date', 
            'selected_time', 
            'eachSlotPrice',
            'subtotal', 
            'serviceCharge', 
            'basePrice', 
            'total', 
            'serviceChargeLabel', 
            'discountAmount',
            'depositAmount',
            'depositLabel'
            ));
    }

    public function webFormBooking(Request $request)
    {
        $request->validate([
            'name'          => 'required|string',
            'email'         => 'required|email',
            'no_ic'         => 'required|string',
            'phone'         => 'nullable|string',
            'country'       => 'nullable|string',
            'state'         => 'nullable|string',
            'city'          => 'nullable|string',
            'postcode'      => 'nullable|string',
            'address'       => 'nullable|string',
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

            $eventCode  = strtoupper($event['shortcode'] ?? 'DFT');
            $totalPrice = 0;

            $booking = Booking::create([
                'participant_id'    => $participant->id,
                'event_id'          => $event['id'],
                'organizer_id'      => $event['organizer_id'] ?? null,
                'booking_code'      => $eventCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(6)),
                'status'            => 'pending',
                'total_price'       => 0,
                'payment_method'    => 'sistemkami-toyyibpay',
                'extra_info'        => ['shirt_size' => $request->shirt_size],
            ]);

            foreach ($tickets as $ticket) {
                $quantity = (int) $ticket['quantity'];
                $price = (float) $ticket['price'];

                for ($i = 0; $i < $quantity; $i++) {
                    BookingTicket::create([
                        'booking_id'        => $booking->id,
                        'ticket_id'         => $ticket['ticket_id'],
                        'price'             => $price,
                        'participant_name'  => $request->name,
                        'participant_email' => $request->email,
                        'participant_no_ic' => $request->no_ic,
                        'participant_phone' => $request->phone,
                        'ticket_code'       => $eventCode . '-' . now()->format('His') . '-' . strtoupper(Str::random(6)),
                        'status'            => 'paid',
                    ]);
                }

                $totalPrice += $price * $quantity;
            }

            $fixed      = $event['service_charge_fixed'] ?? null;
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
                'booking_id'        => $booking->id,
                'total_price'       => $totalPrice,
                'service_charge'    => $serviceCharge,
                'grand_total'       => $grandTotal
            ]);

            $booking->update([
                'total_price'       => $totalPrice,
                'service_charge'    => $serviceCharge
            ]);

            // === ToyyibPay integration ===
            $toyyibPay = new ToyyibPayService();
            $bill = $toyyibPay->createBill([
                'name'          => Str::limit('Payment for ' . $event['title'], 30),
                'description'   => 'Booked by ' . $participant->name,
                'amount'        => $grandTotal,
                'ref_no'        => $booking->booking_code,
                'to'            => $participant->name,
                'email'         => $participant->email,
                'phone'         => $participant->phone,
            ]);

            \Log::info('ToyyibPay bill response', ['bill' => $bill]);

            if (!isset($bill[0]['BillCode'])) {
                throw new \Exception('ToyyibPay: BillCode gagal dicipta');
            }

            Payment::create([
                'booking_id'    => $booking->id,
                'bill_code'     => $bill[0]['BillCode'],
                'ref_no'        => $booking->booking_code,
                'amount'        => $grandTotal,
                'status'        => 'pending',
            ]);

            \Log::info('Payment record created', [
                'booking_id'    => $booking->id,
                'bill_code'     => $bill[0]['BillCode'],
            ]);

            DB::commit();

            $baseUrl = config('toyyibpay.sandbox') ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
            return redirect()->away($baseUrl . '/' . $bill[0]['BillCode']);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Web form booking failed', [
                'message'   => $e->getMessage(),
                'input'     => $request->all()
            ]);

            return back()->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
        }
    }

    public function webFormBookingPackage(Request $request)
    {
        $package        = session('selected_package');
        $selected_date  = session('selected_date');
        $selected_time  = session('selected_time');
        $package_inputs = session('package_inputs');
        if (!$package || !$selected_date || !$package_inputs) {
            return back()->withErrors([
                'message' => 'Package selection session has expired. Please select a package again.'
            ]);
        }
        $request->validate([
            'name'              => 'required|string',
            'email'             => 'required|email|confirmed',
            'email_confirmation' => 'required|email',
            'no_ic'             => 'nullable|string',
            'phone'             => 'required|string',
            'whatsapp_number'   => 'required|string',
            'country'           => 'nullable|string',
            'state'             => 'nullable|string',
            'city'              => 'nullable|string',
            'postcode'          => 'nullable|string',
            'address'           => 'nullable|string',
            'notes'             => 'nullable|string',
            'payment_type'      => 'nullable|string',
        ]);

        DB::beginTransaction();
        $inputAnswers = $request->input('package_inputs', []);
        \Log::info("selected_time");
        \Log::info($selected_time);
        \Log::info($package);
        try {
            \Log::info('Web form booking initiated', $request->only(['name', 'email', 'no_ic']));

            $participant = Participant::create($request->only([
                'name',
                'email',
                'no_ic',
                'phone',
                'whatsapp_number',
                'country',
                'state',
                'city',
                'postcode',
                'address',
            ]));

            if (!$package || !$selected_date) {
                \Log::warning('Session expired or incomplete', ['package' => $package, 'selected_date' => $selected_date]);
                throw new \Exception('Session expired or incomplete. Sila pilih package semula.');
            }

            \Log::info($package);

            $packageCode    = strtoupper($package['package_code'] ?? 'DFT');
            if (!empty($selected_time)){
                $selectedCount  = count($selected_time);
                $totalPrice      = $selectedCount * (float) $package['base_price'];
                $eachSlotPrice  = (float) $package['base_price'];
            } else{
                $totalPrice = (float) $package['base_price']; // or base_price if needed
            }
            $paymentType    = $request->input('payment_type');
            // \Log::info("paymentType");
            // \Log::info($paymentType);
            $booking = Booking::create([
                'participant_id'    => $participant->id,
                'event_id'          => null,
                'package_id'        => $package['id'],
                'organizer_id'      => $package['organizer_id'],
                'booking_code'      => $packageCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(6)),
                'status'            => 'pending',
                'total_price'       => 0,
                'paid_amount'       => 0,
                'payment_method'    => 'sistemkami-toyyibpay',
                'payment_type'       => $paymentType,
            ]);

            if (!empty($selected_time)) {
                foreach ($selected_time as $slot) {
                    BookingsVendorTimeSlot::create([
                        'booking_id'            => $booking->id,
                        'vendor_time_slot_id'   => $slot['slot_id'] ?? ($package['vendorTimeSlots'][0]['id'] ?? null),
                        'booked_date_start'     => $slot['date'] ?? $selected_date,
                        'booked_date_end'       => $slot['date'] ?? $selected_date,
                        'package_id'            => $package['id'],
                        'package_category_id'   => $package['category_id'],
                        'organizer_id'          => $package['organizer_id'],
                        'booked_time_start'     => $slot['booked_time_start'] ?? null,
                        'booked_time_end'       => $slot['booked_time_end'] ?? null,
                        'status'                => 'pending',
                        'notes'                 => $request->input('notes') ?? null,
                    ]);
                }
            } else {
                // fallback if no selected_time passed
                BookingsVendorTimeSlot::create([
                    'booking_id'            => $booking->id,
                    'vendor_time_slot_id'   => $package['vendorTimeSlots'][0]['id'] ?? null,
                    'booked_date_start'     => $selected_date,
                    'booked_date_end'       => $selected_date,
                    'package_id'            => $package['id'],
                    'package_category_id'   => $package['category_id'],
                    'organizer_id'          => $package['organizer_id'],
                    'booked_time_start'     => null,
                    'booked_time_end'       => null,
                    'status'                => 'pending',
                    'notes'                 => $request->input('notes') ?? null,
                ]);
            }

            foreach ($inputAnswers as $groupIndex => $answers) {
                foreach ($answers as $inputId => $answer) {
                    PackageInputAnswer::create([
                        'package_input_id'  => $inputId,
                        'package_id'        => $package['id'],
                        'booking_id'        => $booking->id,
                        'answer'            => is_array($answer) ? json_encode($answer) : $answer,
                    ]);
                }
            }

            $discountAmount = 0;
            if (!empty($package['discounts'])) {
                foreach ($package['discounts'] as $discount) {
                    if ($discount['is_active']) {
                        if ($discount['type'] === 'fixed') {
                            $discountAmount = (float) $discount['amount'];
                        } elseif ($discount['type'] === 'percentage') {
                            $discountAmount = $totalPrice * ((float) $discount['amount'] / 100);
                        }
                        break;
                    }
                }
            }

            $totalPrice = max(0, $totalPrice - $discountAmount);

            $fixed          = (float) ($package['service_charge_fixed'] ?? 0);
            $percentage     = (float) ($package['service_charge_percentage'] ?? 0);
            $serviceCharge  = ($percentage > 0 ? $totalPrice * ($percentage / 100) : $fixed);

            $grandTotal     = round($totalPrice + $serviceCharge, 2);

            // calc deposit amount
            $depositFixed       = (float) $package['deposit_fixed'] ?? 0;
            $depositPercentage  = (float) $package['deposit_percentage'] ?? 0;
            $depositAmount      = ($depositPercentage > 0 ? $grandTotal * ($depositPercentage / 100) : $depositFixed);

            if($paymentType == 'deposit'){
                $paidAmount = $depositAmount;
            }else{
                $paidAmount = $grandTotal;
            }
            \Log::info('Booking package totals calculated', [
                'booking_id'        => $booking->id,
                'total_price'       => $totalPrice,
                'service_charge'    => $serviceCharge,
                'grand_total'       => $grandTotal,
                'deposit_amount'    => $depositAmount,
                'paid_amount'       => $paidAmount,
                'percentage'        => $percentage,
                'depositPercentage' => $depositPercentage,
            ]);

            $booking->update([
                'total_price'       => $totalPrice,
                'paid_amount'       => $paidAmount,
                'service_charge'    => $serviceCharge
            ]);

            // === ToyyibPay integration ===
            $toyyibPay = new ToyyibPayService();
            $bill = $toyyibPay->createBill([
                'name'          => substr('Payment: ' . $package['name'], 0, 30),
                'description'   => 'Booked by ' . $participant->name,
                'amount'        => $paidAmount,
                'ref_no'        => $booking->booking_code,
                'to'            => $participant->name,
                'email'         => $participant->email,
                'phone'         => $participant->phone,
            ]);

            \Log::info('ToyyibPay bill response', ['bill' => $bill]);

            if (!isset($bill[0]['BillCode'])) {
                throw new \Exception('ToyyibPay: BillCode gagal dicipta');
            }

            Payment::create([
                'booking_id'    => $booking->id,
                'bill_code'     => $bill[0]['BillCode'],
                'ref_no'        => $booking->booking_code,
                'amount'        => $paidAmount,
                'status'        => 'pending',
            ]);

            \Log::info('Payment record created', [
                'booking_id'    => $booking->id,
                'bill_code'     => $bill[0]['BillCode'],
            ]);

            DB::commit();

            $baseUrl = config('toyyibpay.sandbox') ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
            return redirect()->away($baseUrl . '/' . $bill[0]['BillCode']);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Web form booking failed', [
                'message'   => $e->getMessage(),
                'input'     => $request->all()
            ]);

            return back()->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
        }
    }

    // ADMIN CREATE BOOKING
    public function webFormBookingPackageByAdmin(Request $request)
    {
        $authUser = auth()->guard('organizer')->user()->load('user');

        \Log::info("Start webFormBookingPackageByAdmin");

        $package_id     = $request->input('package_id');
        $selected_date  = $request->input('selected_date');
        $selected_time  = json_decode($request->input('selected_time'), true) ?? [];
        // $discountAmount  = $request->input('discount');
        $deposit_amount  = $request->input('deposit_amount');
        $addonIds = $request->input('addon_ids', []);
        // $package_inputs = session('package_inputs');
        if (!$package_id || !$selected_date) {
            \Log::info("tendang back");
            return back()->withErrors([
                'message' => 'Package selection session has expired. Please select a package again.'
            ]);
        }
        $request->validate([
            'name'              => 'required|string',
            'email'             => 'nullable|email',
            'email_confirmation' => 'nullable|email',
            'no_ic'             => 'nullable|string',
            'phone'             => 'nullable|string',
            'whatsapp_number'   => 'required|string',
            'country'           => 'nullable|string',
            'state'             => 'nullable|string',
            'city'              => 'nullable|string',
            'postcode'          => 'nullable|string',
            'address'           => 'nullable|string',
            'notes'             => 'nullable|string',
            'payment_type'      => 'required|string',
            'reference'         => 'nullable|string',
            'addon_ids'         => 'nullable|array',
            'addon_ids.*'       => 'exists:package_addons,id',
        ]);

        DB::beginTransaction();
        // $inputAnswers = $request->input('package_inputs', []);
        try {
            \Log::info('Web form booking initiated', $request->only(['name', 'email', 'no_ic']));

            $package = Package::whereHas('organizer', function ($query) use ($authUser) {
                $query->where('id', $authUser->id);
            })
                ->where('id', $package_id)
                ->with([
                    'addons',
                    'items',
                    'discounts',
                    'category',
                    'images',
                    'organizer',
                    'vendorTimeSlots',
                ])
                ->firstOrFail();

                $data = $request->only([
                    'name',
                    'email',
                    'no_ic',
                    'phone',
                    'whatsapp_number',
                    'country',
                    'state',
                    'city',
                    'postcode',
                    'address',
                ]);

                $selectedAddons = $package->addons()
                    ->whereIn('id', $addonIds)
                    ->get();

                // If phone is empty, use whatsapp_number
                $data['phone'] = $data['whatsapp_number'];
                $data['email'] = $authUser->email;

                $participant = Participant::create($data);


            if (!$package_id || !$selected_date) {
                \Log::warning('Session expired or incomplete', ['package' => $package, 'selected_date' => $selected_date]);
                throw new \Exception('Session expired or incomplete. Sila pilih package semula.');
            }

            $addonTotal = 0;

            /* ---------- NORMAL ADDONS (checkbox) ---------- */
            if ($request->filled('addon_ids')) {

                $selectedAddons = PackageAddon::whereIn('id', $request->addon_ids)->get();

                foreach ($selectedAddons as $addon) {
                    $addonTotal += (float) $addon->price;
                }
            }

            /* ---------- QTY ADDONS ---------- */
            if ($request->filled('addon_qty')) {

                foreach ($request->addon_qty as $addonId => $qty) {

                    $qty = (int) $qty;

                    if ($qty <= 0) {
                        continue;
                    }

                    $addon = PackageAddon::find($addonId);

                    if (!$addon) {
                        continue;
                    }

                    $addonTotal += (float) $addon->price * $qty;
                }
            }



            $packageCode      = strtoupper($package['package_code'] ?? 'DFT');
            $basePackagePrice = (float) $package->base_price;
            $slotQtyRequired = $package->package_slot_quantity ?? 1;

            $selectedCount = !empty($selected_time) ? count($selected_time) : 0;

            // how many full packages
            $packageQty = $selectedCount > 0
                ? intdiv($selectedCount, $slotQtyRequired)
                : 1;

            // calculate package total
            $packageTotal = $packageQty * $basePackagePrice;

            /* ADDON PRICE HERE */
            $totalPrice = $packageTotal + $addonTotal;

            $paymentType    = $request->input('payment_type');
            $booking = Booking::create([
                'participant_id'    => $participant->id,
                'event_id'          => null,
                'package_id'        => $package['id'],
                'organizer_id'      => $package['organizer_id'],
                'booking_code'      => $packageCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(6)),
                'status'            => 'paid',
                'total_price'       => 0,
                'paid_amount'       => 0,
                'payment_method'    => 'sistemkami',
                'payment_type'      => $paymentType,
            ]);

            /* ---------- SAVE ADDONS INTO PIVOT TABLE ---------- */

            $syncData = [];

            /* 1️⃣ Checkbox addons (normal add-ons) */
            if ($request->has('addon_ids')) {
                foreach ($request->addon_ids as $addonId) {
                    $syncData[$addonId] = ['qty' => 1];
                }
            }

            /* 2️⃣ Quantity addons (Tambahan Orang etc) */
            if ($request->has('addon_qty')) {
                foreach ($request->addon_qty as $addonId => $qty) {

                    $qty = (int) $qty;

                    // only save if > 0
                    if ($qty > 0) {
                        $syncData[$addonId] = ['qty' => $qty];
                    }
                }
            }

            $booking->addons()->sync($syncData);


            $depositAmount = $deposit_amount;


            $discountAmount = (float) $request->input('discount', 0);

            $totalPrice = max(0, $totalPrice - $discountAmount);
            $grandTotal     = round($totalPrice, 2);

            if($paymentType == 'deposit'){
                $paidAmount = $depositAmount;
                $paymentTypeStatus = 'deposit_paid';
            }else{
                $paidAmount = $grandTotal;
                $paymentTypeStatus = 'full_payment';
            }

            $slotDurations = collect($package->vendorTimeSlots)
                ->mapWithKeys(function ($slot) use ($package) {
                    return [$slot->id => $package->duration_minutes];
                });

            if (!empty($selected_time)) {
                foreach ($selected_time as $slot) {

                    $duration = $slotDurations[$slot['id']] ?? 0;

                    $start = Carbon::createFromFormat('g:i A', $slot['time']);
                    $end   = $duration > 0
                        ? $start->copy()->addMinutes($duration)
                        : null;

                    BookingsVendorTimeSlot::create([
                        'booking_id'          => $booking->id,
                        'vendor_time_slot_id' => $slot['id'],
                        'booked_date_start'   => $slot['date'],
                        'booked_date_end'     => $slot['date'],
                        'package_id'          => $package->id,
                        'package_category_id' => $package->category_id,
                        'organizer_id'        => $package->organizer_id,
                        'booked_time_start'   => $start->format('H:i:s'),
                        'booked_time_end'     => $end?->format('H:i:s'),
                        'status'              => $paymentTypeStatus,
                        'notes'               => $request->input('notes'),
                    ]);
                }
            } else {
                // fallback if no selected_time passed
                BookingsVendorTimeSlot::create([
                    'booking_id'            => $booking->id,
                    'vendor_time_slot_id'   => $package['vendorTimeSlots'][0]['id'] ?? null,
                    'booked_date_start'     => $selected_date,
                    'booked_date_end'       => $selected_date,
                    'package_id'            => $package['id'],
                    'package_category_id'   => $package['category_id'],
                    'organizer_id'          => $package['organizer_id'],
                    'booked_time_start'     => null,
                    'booked_time_end'       => null,
                    'status'                => 'pending',
                    'notes'                 => $request->input('notes') ?? null,
                ]);
            }

            // foreach ($inputAnswers as $groupIndex => $answers) {
            //     foreach ($answers as $inputId => $answer) {
            //         PackageInputAnswer::create([
            //             'package_input_id'  => $inputId,
            //             'package_id'        => $package['id'],
            //             'booking_id'        => $booking->id,
            //             'answer'            => is_array($answer) ? json_encode($answer) : $answer,
            //         ]);
            //     }
            // }


            // $fixed          = (float) ($package['service_charge_fixed'] ?? 0);
            // $percentage     = (float) ($package['service_charge_percentage'] ?? 0);
            // $serviceCharge  = ($percentage > 0 ? $totalPrice * ($percentage / 100) : $fixed);

            // $grandTotal     = round($totalPrice + $serviceCharge, 2);

            // calc deposit amount
            // $depositFixed       = (float) $package['deposit_fixed'] ?? 0;
            // $depositPercentage  = (float) $package['deposit_percentage'] ?? 0;
            // $depositAmount      = ($depositPercentage > 0 ? $grandTotal * ($depositPercentage / 100) : $depositFixed);
            
            \Log::info('Booking package totals calculated', [
                'booking_id'        => $booking->id,
                'total_price'       => $totalPrice,
                // 'service_charge'    => $serviceCharge,
                'grand_total'       => $grandTotal,
                'deposit_amount'    => $depositAmount,
                'paid_amount'       => $paidAmount,
                // 'percentage'        => $percentage,
                // 'depositPercentage' => $depositPercentage,
            ]);

            $booking->update([
                'total_price'       => $totalPrice + $discountAmount,
                'discount'          => $discountAmount,
                'paid_amount'       => $paidAmount,
                // 'service_charge'    => $serviceCharge
            ]);

            // === ToyyibPay integration ===
            // $toyyibPay = new ToyyibPayService();
            // $bill = $toyyibPay->createBill([
            //     'name'          => substr('Payment: ' . $package['name'], 0, 30),
            //     'description'   => 'Booked by ' . $participant->name,
            //     'amount'        => $paidAmount,
            //     'ref_no'        => $booking->booking_code,
            //     'to'            => $participant->name,
            //     'email'         => $participant->email,
            //     'phone'         => $participant->phone,
            // ]);

            // \Log::info('ToyyibPay bill response', ['bill' => $bill]);

            // if (!isset($bill[0]['BillCode'])) {
            //     throw new \Exception('ToyyibPay: BillCode gagal dicipta');
            // }

            $billCode = $this->generateUniqueBillCode();

            Payment::create([
                'booking_id'    => $booking->id,
                'bill_code'     => $billCode,
                'ref_no'        => $booking->booking_code,
                'amount'        => $paidAmount,
                'status'        => $paymentTypeStatus,
            ]);

            \Log::info('Payment record created', [
                'booking_id'    => $booking->id,
                'bill_code'     => $billCode,
            ]);


            $booking = Booking::where('id', $booking->id)
                    ->with([
                        'package',
                        'vendorTimeSlots',
                    ])
                    ->firstOrFail();

            // Send email to booking's email
            // Mail::to($booking->participant->email)->send(new PaymentConfirmed($booking));

            // EmailLog::create([
            //     'to_email' => $booking->participant->email,
            //     'type' => 'payment_confirmed',
            //     'sent_at' => now(),
            // ]);

            // $baseUrl = config('toyyibpay.sandbox') ? 'https://dev.toyyibpay.com' : 'https://toyyibpay.com';
            // return redirect()->away($baseUrl . '/' . $bill[0]['BillCode']);

            DB::commit();

            // return redirect()->route('booking.receipt.package', $booking->booking_code);
            $receiptUrl = route('booking.receipt.package', $booking->booking_code);

            $phone = $data['whatsapp_number'];

           // Mulakan mesej
        $text = "Hai " . $data['name'] . ",\n\n";
        $text .= "Tempahan anda telah berjaya dibuat untuk Pakej "
            . $booking->package->name
            . " oleh "
            . $authUser->name
            . ".\n\n";

        // Info pembayaran
        if ($booking->payment_type === 'deposit') {
            if ($booking->paid_amount > 0) {
                $text .= "*Status Pembayaran*: Deposit diterima\n";
                $text .= "Jumlah Deposit: RM" . number_format($booking->paid_amount, 2) . "\n";
            } else {
                $text .= "*Status Pembayaran*: Tiada deposit dibayar\n";
            }
            $text .= "Baki Perlu Dibayar: RM" . number_format(
                ($booking->total_price + ($booking->service_charge ?? 0) - ($booking->discount ?? 0)) - $booking->paid_amount,
                2
            ) . "\n\n";
        } else {
            $text .= "*Status Pembayaran*: Penuh (Selesai)\n";
            $text .= "Jumlah Dibayar: RM" . number_format($booking->paid_amount, 2) . "\n\n";
        }

        // Tambah tarikh & masa setiap slot
        foreach ($booking->vendorTimeSlots as $slot) {
            $slotName  = $slot->timeSlot->slot_name ?? '-';
            $startDate = \Carbon\Carbon::parse($slot->booked_date_start)->format('d M Y');
            $startTime = \Carbon\Carbon::parse($slot->booked_time_start)->format('h:i A');
            $endTime   = \Carbon\Carbon::parse($slot->booked_time_end)->format('h:i A');

            $text .= "📌 Slot: " . $slotName . "\n";
            $text .= "🗓 Tarikh: " . $startDate . "\n";
            $text .= "⏰ Masa: " . $startTime . " - " . $endTime . "\n\n";
        }

        if ($booking->addons->count()) {
            $text .= "*Add Ons Dipilih:*\n";

            foreach ($booking->addons as $addon) {

                $qty = $addon->pivot->qty ?? 1;
                $lineTotal = $addon->price * $qty;

                // If qty addon
                if ($qty > 1) {
                    $text .= "- {$addon->name} x{$qty}" . "\n";
                } else {
                    $text .= "- {$addon->name}" . "\n";
                }
            }

            $text .= "\n";
        }


        // Tambah pautan resit
        if ($booking->payment_type === 'deposit') {
            $text .= "📄 Resit Deposit boleh dimuat turun di sini:\n";
        } else {
            $text .= "📄 Resit Pembayaran Penuh boleh dimuat turun di sini:\n";
        }

        $text .= $receiptUrl . "\n\n";
        $text .= "Terima kasih kerana menggunakan perkhidmatan kami 🙏";

        // Link WhatsApp (tanpa '+')
        $whatsappUrl = 'https://api.whatsapp.com/send?phone=+6' . $phone
            . '&text=' . urlencode($text);

        return redirect()->back()->with([
            'success' => 'Booking for ' . $data['name'] . ' was created successfully!',
            'whatsapp_url' => $whatsappUrl,
        ]);


        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Web form booking failed', [
                'message'   => $e->getMessage(),
                'input'     => $request->all()
            ]);
            return redirect()->back()->with('error', 'Booking failed!');

            // return back()->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
        }
    }

    public function handleCallback(Request $request)
    {
        \Log::info('ToyyibPay callback received:', $request->all());

        if (!$request->billcode) {
            \Log::warning('Callback tanpa payment (invalid bill_code):', $request->all());
            // return response()->json(['error' => 'Invalid bill code'], 404);
            abort(400, 'Invalid bill code');
        }
        
        $payment = Payment::where('bill_code', $request->billcode)->first();

        if (!$payment) {
            \Log::warning('Callback tanpa payment (invalid bill_code):', $request->all());
            // return response()->json(['error' => 'Invalid bill code'], 404);
            abort(400, 'Invalid bill code');
        }

        $status = $request->status_id == 1 ? 'paid' : 'failed';

        $payment->update([
            'status'        => $status,
            'paid_at'       => now(),
            'raw_response'  => $request->all(),
        ]);

        \Log::info('Payment updated:', [
            'payment_id'    => $payment->id,
            'status'        => $status,
        ]);

        if ($status === 'paid') {

            $booking = $payment->booking;
            $payment->booking->update(['status' => 'paid']);

            if ($booking->package_id) {
                if ($booking->payment_type == 'deposit') {
                    BookingsVendorTimeSlot::where('booking_id', $booking->id)
                        ->update(['status' => 'deposit_paid']);
                } else {
                    BookingsVendorTimeSlot::where('booking_id', $booking->id)
                        ->update(['status' => 'full_payment']);
                }
            }

            \Log::info('Booking marked as paid:', ['booking_id' => $payment->booking->id]);
            return redirect()->route('booking.receipt', $booking->booking_code);
        } else {

            $booking = $payment->booking;
            $payment->booking->update(['status' => 'failed']);

            if ($booking->package_id) {
                BookingsVendorTimeSlot::where('booking_id', $booking->id)
                    ->update(['status' => 'failed']);
            }

            \Log::warning('Booking marked as failed:', [
                'booking_id' => $payment->booking->id,
                'reason' => $request->description ?? 'No reason given'
            ]);
            return redirect()->route('booking.receipt');
        }
    }

    public function bookingReceipt($booking_code)
    {
        $page_title = 'Your Booking Receipt';
        $booking = Booking::with(['package', 'event', 'vendorTimeSlot'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('home.booking.receipt', compact('booking', 'page_title'));
    }

    public function bookingReceiptPackage($booking_code)
    {
        $page_title = 'Your Booking Receipt';
        $booking = Booking::with(['package', 'package', 'vendorTimeSlots'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('home.booking.receipt', compact('booking', 'page_title'));
    }

    private function generateUniqueBillCode(): string
    {
        do {
            // Example: BILL-8F3K2A9X
            $billCode = 'BILL-' . strtoupper(Str::random(8));
        } while (Payment::where('bill_code', $billCode)->exists());

        return $billCode;
    }
}