<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Organizer extends Authenticatable
{
    use HasFactory, SoftDeletes;

    /**
     * what_flow values:
     *  1 = default: packages are packages, slots are slots
     *  2 = reversed: "slot" is the sellable unit shown as package on profile, "package" is the grouping shown as slot
     *  3 = simple booking input: no slot selection on organizer profile, just a direct booking form
     *  4 = no slot input shown on organizer profile at all
     */
    const FLOW_DEFAULT          = 1;
    const FLOW_SLOT_AS_PACKAGE  = 2;
    const FLOW_SIMPLE_BOOKING   = 3;
    const FLOW_NO_SLOT          = 4;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'category',
        'email',
        'phone',
        'fonnte_token',
        'auto_send_receipt',
        'reminder_quiet_start',
        'reminder_quiet_end',
        'website',
        'social_links',
        'address_line1',
        'address_line2',
        'office_name',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'is_gmaps_verified',
        'google_map_show',
        'logo_path',
        'banner_path',
        'payment_qr_path',
        'wallet_balance',
        'wallet_currency',
        'wallet_history',
        'is_active',
        'visibility',
        'what_flow',
        'special_page',
        'user_id',
    ];

    protected $casts = [
        'banner_path'       => 'array',
        'social_links'      => 'array',
        'wallet_history'    => 'array',
        'is_active'         => 'boolean',
        'is_gmaps_verified' => 'boolean',
        'google_map_show'   => 'boolean',
        'wallet_balance'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function activePackages(): HasMany
    {
        return $this->hasMany(Package::class)->where('status', 'active')->orderBy('order_by');
    }

    public function inactivePackages(): HasMany
    {
        return $this->hasMany(Package::class)->where('status', 'inactive')->orderBy('order_by');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(BusinessGallery::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(VendorTimeSlot::class);
    }

    public function offDays(): HasMany
    {
        return $this->hasMany(VendorOffDay::class);
    }

    public function bookedTimeSlots(): HasMany
    {
        return $this->hasMany(BookingsVendorTimeSlot::class, 'organizer_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if ($this->logo_path) {
            return asset('storage/uploads/' . $this->id . '/' . $this->logo_path);
        }

        return asset('storage/uploads/default-organizer-logo.jpg');
    }

    public function getPaymentQrUrlAttribute(): ?string
    {
        if ($this->payment_qr_path) {
            return route('organizer.payment.qr', $this->slug);
        }

        return null;
    }
}
