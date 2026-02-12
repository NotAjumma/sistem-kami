<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $fillable = [
        'organizer_id',
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'final_price',
        'discount_percentage',
        'status',
        'valid_from',
        'valid_until',
        'max_booking_year_offset',
    ];

    protected $casts = [
        'base_price'                => 'decimal:2',
        'final_price'               => 'decimal:2',
        'discount_percentage'       => 'integer',
        'max_booking_year_offset'   => 'integer',
        'valid_from'                => 'datetime',
        'valid_until'               => 'datetime',
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

    public function images()
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order')->orderBy('sort_order', 'asc');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(PackageDiscount::class);
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

}
