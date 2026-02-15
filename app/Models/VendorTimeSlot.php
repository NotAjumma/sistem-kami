<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'package_id',
        'is_full_day',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'is_active',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
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
