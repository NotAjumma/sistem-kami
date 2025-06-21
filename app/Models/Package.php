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
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'discount_percentage' => 'integer',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
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
        return $this->hasMany(PackageItem::class);
    }

    public function addons(): HasMany
    {
        return $this->hasMany(PackageAddon::class);
    }

    public function images()
    {
        return $this->hasMany(PackageImage::class)->orderBy('sort_order');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(PackageDiscount::class);
    }

    public function vendorTimeSlots()
    {
        return $this->hasMany(VendorTimeSlot::class, 'package_id');
    }

}
