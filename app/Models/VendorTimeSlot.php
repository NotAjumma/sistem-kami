<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_name',
        'slot_price',
        'pax',
        'organizer_id',
        'is_full_day',
        'start_time',
        'end_time',
        'duration_minutes',
        'rest_minutes',
        'is_multiple',
        'is_active',
    ];

    protected $casts = [
        'slot_price'   => 'decimal:2',
        'is_full_day'  => 'boolean',
        'is_multiple'  => 'boolean',
        'is_active'    => 'boolean',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function bookings()
    {
        return $this->hasMany(BookingsVendorTimeSlot::class);
    }

    public function images()
    {
        return $this->hasMany(SlotImage::class, 'slot_id')->orderBy('sort_order', 'asc');
    }
}
