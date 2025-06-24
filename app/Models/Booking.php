<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'participant_id',
        'event_id',
        'package_id',
        'organizer_id',
        'booking_code',
        'total_price',
        'resit_path',
        'payment_method',
        'status',
        'coupon_code',
        'discount',
        'service_charge',
        'final_price',
        'extra_info',
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

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function vendorTimeSlots()
    {
        return $this->hasMany(BookingsVendorTimeSlot::class);
    }

}
