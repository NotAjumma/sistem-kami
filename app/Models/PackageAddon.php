<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageAddon extends Model
{
    protected $fillable = [
        'package_id',
        'name',
        'description',
        'price',
        'is_required',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_required' => 'boolean',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
