<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingAddon extends Model
{
    use HasFactory;

    protected $table = 'booking_addon';

    protected $fillable = [
        'booking_id',
        'addon_id',
        'qty',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Belongs to Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Belongs to Addon
    public function addon()
    {
        return $this->belongsTo(PackageAddon::class, 'addon_id');
    }
}
