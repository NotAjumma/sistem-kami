<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participant;
use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class BookingController extends Controller
{
    public function sync(Request $request)
    {
        $data = $request->all();

        // Hardcode for ticket event strike joran RM100/joran
        $ticket_id = 2;
        DB::beginTransaction();

        try {
            // 1. Simpan atau update participant
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
            $eventCode = strtoupper($ticket->event->shortcode ?? 'DFT');

            // 2. Buat booking baru
            $resitPath = null;

            if ($request->has('resit_pembayaran_base64')) {
                $base64 = $request->input('resit_pembayaran_base64');
                $filename = $request->input('resit_filename', 'resit_' . time() . '.jpg');

                $fileData = base64_decode($base64);

                // Define local path relative to project root
                $localPath = public_path('images/resit/' . $filename);

                // Pastikan folder wujud
                if (!file_exists(dirname($localPath))) {
                    mkdir(dirname($localPath), 0755, true);
                }

                // Simpan file
                file_put_contents($localPath, $fileData);

                // Simpan path relatif untuk database (contoh: images/resit/filename.jpg)
                $resitPath = 'images/resit/' . $filename;
            }

            // Buat booking baru
            $booking = Booking::create([
                'participant_id' => $participant->id,
                'booking_code' => $eventCode . '-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'resit_path' => $resitPath ?? null,
            ]);


            // 3. Cari ticket type (master ticket)
            $ticket = Ticket::where('id', $ticket_id)->first();
            if (!$ticket) {
                DB::rollBack();
                return response()->json(['error' => 'Invalid ticket type'], 400);
            }

            // 4. Buat booking ticket detail
            $bilanganJoran = (int) ($data['bilangan_joran'] ?? 1);
            for ($i = 0; $i < $bilanganJoran; $i++) {
                BookingTicket::create([
                    'booking_id' => $booking->id,
                    'ticket_id' => $ticket->id,
                    'participant_name' => $data['participant_name'] ?? $data['name'],
                    'participant_email' => $data['email'],
                    'participant_no_ic' => $data['no_ic'] ?? null,
                    'participant_phone' => $data['phone'] ?? null,
                    'ticket_code' => $eventCode . '-' . now()->format('ymdHis') . '-' . strtoupper(Str::random(3)),
                    'status' => 'available',
                ]);
            }


            DB::commit();

            return response()->json(['message' => 'Sync successful'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Sync failed', 'details' => $e->getMessage()], 500);
        }
    }
}