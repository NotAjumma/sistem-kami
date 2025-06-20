<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorTimeSlotLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'package_id',
        'package_category_id',
        'duration_unit',
        'duration_value',
        'booking_limit',
        'starts_at',
        'ends_at',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function category()
    {
        return $this->belongsTo(PackageCategory::class, 'package_category_id');
    }
}
