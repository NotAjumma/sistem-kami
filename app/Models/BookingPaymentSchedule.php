<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingPaymentSchedule extends Model
{
    use HasFactory;

    protected $table = 'booking_payment_schedules';

    protected $fillable = [
        'booking_id',
        'booking_code',
        'package_id',
        'organizer_id',
        'due_date',
        'amount',
        'payment_type',
        'is_paid',
        'paid_at',
    ];

    protected $casts = [
        'due_date'   => 'date',
        'paid_at'    => 'datetime',
        'is_paid'    => 'boolean',
        'amount'     => 'decimal:2',
    ];

    // Relationships

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}
