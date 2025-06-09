<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'bill_code',
        'ref_no',
        'amount',
        'status',
        'paid_at',
        'raw_response',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'raw_response' => 'array',
    ];

    // Relationship: 1 Payment belongs to 1 Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper: check if payment is completed
    public function isPaid()
    {
        return $this->status === 'paid';
    }
}
