<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'participant_id',
        'event_id',
        'booking_code',
        'total_price',
        'resit_path',
        'payment_method',
        'status',
        'coupon_code',
        'discount',
        'final_price',
    ];

    protected $casts = [
        'extra_info' => 'array',
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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
