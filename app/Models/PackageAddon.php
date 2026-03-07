<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageAddon extends Model
{
    protected $fillable = [
        'package_id',
        'name',
        'hint',
        'description',
        'price',
        'is_qty',
        'is_required',
        'is_time',
        'time_minutes',
        'special_date_start',
        'special_date_end',
        'special_date',
    ];

    protected $casts = [
        'price'              => 'decimal:2',
        'is_required'        => 'boolean',
        'is_qty'             => 'boolean',
        'is_time'            => 'boolean',
        'special_date_start' => 'date',
        'special_date_end'   => 'date',
        'special_date'       => 'date',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_addon');
    }

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class, 'addon_id');
    }
}
