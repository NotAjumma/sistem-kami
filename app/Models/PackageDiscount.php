<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageDiscount extends Model
{
    protected $fillable = [
        'package_id',
        'type',
        'amount',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}

