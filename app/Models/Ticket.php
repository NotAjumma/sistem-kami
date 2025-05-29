<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'event_id',
        'parent_id',
        'name',
        'description',
        'price',
        'quantity',
        'is_active',
        'order_by',
        'release_date',
    ];

    protected $casts = [
        'release_date' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'parent_id')
            ->where('is_active', 1);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Ticket::class, 'parent_id')
            ->where('is_active', 1)
            ->orderBy('order_by', 'asc');
    }

    public function scopeReleased($query)
    {
        return $query->whereNull('release_date')
            ->orWhere('release_date', '<=', now());
    }

}
