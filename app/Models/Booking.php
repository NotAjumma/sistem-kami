<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'participant_id',
        'booking_code',
        'status',
    ];

    // Relasi ke participant
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    // Relasi ke booking tickets (details)
    public function bookingTickets()
    {
        return $this->hasMany(BookingTicket::class);
    }
}
