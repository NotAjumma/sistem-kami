<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organizer_id',
        'title',
        'slug',
        'shortcode',
        'description',
        'venue_name',
        'location',
        'district',
        'state',
        'country',
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'ticket_price',
        'currency',
        'capacity',
        'is_free',
        'service_charge_fixed',
        'service_charge_percentage',
        'buy_link',
        'status',
        'registration_deadline',
        'images',
        'image_cover',
        'category_id',
    ];

    protected $casts = [
        'images'                => 'array',
        'is_free'               => 'boolean',
        'service_charge_fixed'  => 'decimal:2',
        'ticket_price'          => 'decimal:2',
        'start_date'            => 'date',
        'end_date'              => 'date',
        'registration_deadline' => 'datetime',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(Organizer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class)
            ->where('is_active', 1)
            ->whereNull('parent_id')
            ->orderBy('order_by', 'asc')
            ->with(['children']);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
