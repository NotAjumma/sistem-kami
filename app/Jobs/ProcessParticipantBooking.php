<?php
namespace App\Jobs;

use App\Models\Participant;
use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessParticipantBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        $data = $this->data;

        // Cari atau cipta participant
        $participant = Participant::firstOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name']]
        );

        // Elak duplicate booking (optional: ikut keperluan)
        $bookingExists = Booking::where('participant_id', $participant->id)
            ->whereDate('created_at', today())
            ->exists();

        if ($bookingExists) {
            return;
        }

        // Create booking
        $booking = Booking::create([
            'participant_id' => $participant->id,
            'booking_code' => strtoupper(uniqid('BOOK-')),
            'status' => 'confirmed', // atau 'pending', etc
        ]);

        // Hantar email pengesahan
        Mail::to($participant->email)->send(new BookingConfirmationMail($participant, $booking));
    }
}
