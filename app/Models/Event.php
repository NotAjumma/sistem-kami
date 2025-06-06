<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'ticket_price',
        'buy_link',
        'images',
        'shortcode',
    ];

    protected $casts = [
        'images' => 'array',
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
