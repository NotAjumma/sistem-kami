<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\Payment;
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

    public function showCheckout(Request $request)
    {
        return view('home.checkout');
    }


    public function webFormBooking(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'no_ic' => 'required|string',
            'phone' => 'nullable|string',
            'shirt_size' => 'nullable|string',
            'bilangan_joran' => 'nullable|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $participant = Participant::updateOrCreate(
                [
                    'email' => $request->email,
                    'no_ic' => $request->no_ic,
                ],
                [
                    'name' => $request->name,
                    'phone' => $request->phone,
                ]
            );

            $ticket_id = 2;
            $ticket = Ticket::with('event')->findOrFail($ticket_id);
            $eventCode = strtoupper($ticket->event->shortcode ?? 'DFT');

            $bilanganJoran = max(1, (int) $request->bilangan_joran);
            $totalPrice = $ticket->price * $bilanganJoran;

            $booking = Booking::create([
                'participant_id' => $participant->id,
                'event_id' => $ticket->event_id,
                'organizer_id' => $ticket->event->organizer_id,
                'booking_code' => $eventCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(6)),
                'status' => 'pending',
                'total_price' => $totalPrice,
                'payment_method' => 'webform',
                'extra_info' => ['shirt_size' => $request->shirt_size],
            ]);

            for ($i = 0; $i < $bilanganJoran; $i++) {
                BookingTicket::create([
                    'booking_id' => $booking->id,
                    'ticket_id' => $ticket->id,
                    'price' => $ticket->price,
                    'participant_name' => $request->name,
                    'participant_email' => $request->email,
                    'participant_no_ic' => $request->no_ic,
                    'participant_phone' => $request->phone,
                    'ticket_code' => $eventCode . '-' . now()->format('His') . '-' . strtoupper(Str::random(6)),
                    'status' => 'pending',
                ]);
            }

            // === ToyyibPay integration ===
            $toyyibPay = new ToyyibPayService();
            $bill = $toyyibPay->createBill([
                'name' => 'Bayaran Tiket ' . $ticket->event->name,
                'description' => 'Tempahan oleh ' . $participant->name,
                'amount' => $totalPrice,
                'ref_no' => $booking->booking_code,
                'to' => $participant->name,
                'email' => $participant->email,
                'phone' => $participant->phone,
            ]);

            if (!isset($bill[0]['BillCode'])) {
                throw new \Exception('ToyyibPay: BillCode gagal dicipta');
            }

            Payment::create([
                'booking_id' => $booking->id,
                'bill_code' => $bill[0]['BillCode'],
                'ref_no' => $booking->booking_code,
                'amount' => $totalPrice,
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->away('https://sandbox.toyyibpay.com/' . $bill[0]['BillCode']);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('webFormBooking failed', [
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);

            return back()->with('error', 'Pendaftaran gagal: ' . $e->getMessage());
        }
    }

    public function handleCallback(Request $request)
    {
        $payment = Payment::where('bill_code', $request->billcode)->first();

        if (!$payment) {
            \Log::warning('Callback tanpa payment: ', $request->all());
            return response()->json(['error' => 'Invalid bill code'], 404);
        }

        $payment->update([
            'status' => $request->status_id == 1 ? 'paid' : 'failed',
            'paid_at' => now(),
            'raw_response' => $request->all(),
        ]);

        // Kemaskini status booking jika berjaya
        if ($request->status_id == 1) {
            $payment->booking->update([
                'status' => 'paid'
            ]);
        }

        return response()->json(['success' => true]);
    }



}