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
        'is_manual',
    ];

    protected $casts = [
        'base_price'                => 'decimal:2',
        'final_price'               => 'decimal:2',
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

    public function getDisplayImageUrlAttribute()
    {
        // 1️⃣ Package images
        $image = $this->images()->first();
        if ($image) {
            return asset(
                'storage/uploads/' .
                $this->organizer_id .
                '/packages/' .
                $this->id . '/' .
                $image->url
            );
        }

        // 2️⃣ Slot images (ambil first slot yg ada image)
        foreach ($this->vendorTimeSlots as $slot) {

            $slotImage = $slot->images->first();

            if ($slotImage) {
                return asset(
                    'storage/uploads/' .
                    $this->organizer_id .
                    '/slots/' .
                    $slotImage->url
                );
            }
        }

        // 3️⃣ Organizer logo
        if ($this->organizer && $this->organizer->logo_url) {
            return $this->organizer->logo_url;
        }

        // 4️⃣ Organizer banner
        if ($this->organizer && $this->organizer->banner_path) {
            return $this->organizer->banner_path;
        }

        // 5️⃣ Default image
        return asset('storage/uploads/default-organizer-logo.jpg');
    }
}
