<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotImage extends Model
{
    use HasFactory;

    protected $table = 'slot_images';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slot_id',      // references vendor_time_slots.id
        'url',
        'alt_text',
        'is_cover',
        'sort_order',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'is_cover' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the vendor time slot that owns the image.
     */
    public function vendorTimeSlot()
    {
        return $this->belongsTo(VendorTimeSlot::class, 'slot_id');
    }
}
