<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingsVendorTimeSlot extends Model
{
    use HasFactory;

    protected $table = 'bookings_vendor_time_slot';

    protected $fillable = [
        'booking_id',
        'vendor_time_slot_id',
        'booked_date_start',
        'booked_date_end',
        'package_id',
        'package_category_id',
        'organizer_id', 
        'booked_time_start',
        'booked_time_end',
        'notes',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(VendorTimeSlot::class, 'vendor_time_slot_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function scopePaidStatuses($query)
    {
        return $query->whereIn('status', ['deposit_paid', 'full_payment']);
    }
}
