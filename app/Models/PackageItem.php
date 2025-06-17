<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItem extends Model
{
    protected $fillable = [
        'package_id',
        'title',
        'description',
        'quantity',
        'unit_price',
        'is_optional',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'is_optional' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
