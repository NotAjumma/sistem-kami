<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Organizer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_id',
    ];

    protected $casts = [
        'banner_path' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return asset('images/organizers/' . $this->id . '/' . $this->logo_path);
        }

        // fallback to default logo if no logo_path set
        return asset('images/organizers/default-organizer-logo.jpg');
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function activePackages()
    {
        return $this->hasMany(Package::class)->where('status', 'active');
    }

    public function gallery()
    {
        return $this->hasMany(BusinessGallery::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(VendorTimeSlot::class);
    }

    public function offDays()
    {
        return $this->hasMany(VendorOffDay::class);
    }

    public function bookedTimeSlots()
    {
        return $this->hasMany(BookingsVendorTimeSlot::class, 'organizer_id');
    }

}
