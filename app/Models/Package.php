<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'organizer_id',
        'category_id',
        'name',
        'slug',
        'package_code',
        'description',
        'tnc',
        'base_price',
        'final_price',
        'discount_percentage',
        'service_charge_fixed',
        'service_charge_percentage',
        'deposit_percentage',
        'deposit_fixed',
        'duration_minutes',
        'rest_minutes',
        'package_slot_quantity',
        'order_by',
        'status',
        'valid_from',
        'valid_until',
        'last_paid_date',
        'max_booking_year_offset',
        'is_manual',
        'exclude_vendor_time_slots',
    ];

    protected $casts = [
        'base_price'                => 'decimal:2',
        'final_price'               => 'decimal:2',
        'service_charge_fixed'      => 'decimal:2',
        'service_charge_percentage' => 'decimal:2',
        'deposit_percentage'        => 'decimal:2',
        'deposit_fixed'             => 'decimal:2',
        'discount_percentage'       => 'integer',
        'max_booking_year_offset'   => 'integer',
        'is_manual'                 => 'integer',
        'valid_from'                => 'datetime',
        'valid_until'               => 'datetime',
        'exclude_vendor_time_slots' => 'array',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PackageCategory::class, 'category_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PackageItem::class)->orderBy('sort_order', 'asc');
    }

    public function addons(): HasMany
    {
        return $this->hasMany(PackageAddon::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order', 'asc');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(PackageDiscount::class);
    }

    public function formFields(): HasMany
    {
        return $this->hasMany(BookingFormField::class);
    }

    public function inputs(): HasMany
    {
        return $this->hasMany(PackageInput::class)->orderBy('sort_order', 'asc');
    }

    public function vendorTimeSlots()
    {
        return $this->hasMany(VendorTimeSlot::class, 'organizer_id', 'organizer_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSortedAsc($query)
    {
        return $query->orderBy('order_by', 'asc');
    }

    public function scopeSortedDesc($query)
    {
        return $query->orderBy('order_by', 'desc');
    }

    public function getDisplayImageUrlAttribute()
    {
        $image = $this->images()->first();
        if ($image) {
            return asset('storage/uploads/' . $this->organizer_id . '/packages/' . $this->id . '/' . $image->url);
        }

        foreach ($this->vendorTimeSlots as $slot) {
            $slotImage = $slot->images->first();
            if ($slotImage) {
                return asset('storage/uploads/' . $this->organizer_id . '/slots/' . $slot->id . '/' . $slotImage->url);
            }
        }

        if ($this->organizer && $this->organizer->logo_url) {
            return $this->organizer->logo_url;
        }

        return asset('storage/uploads/default-organizer-logo.jpg');
    }
}
