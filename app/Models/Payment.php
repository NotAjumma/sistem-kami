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
        'payment_type',
        'is_final',
        'method',
        'status',
        'paid_at',
        'raw_response',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'raw_response' => 'array',
        'amount'       => 'decimal:2',
        'is_final'     => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
