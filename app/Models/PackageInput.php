<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageInput extends Model
{
    use HasFactory;

    protected $table = 'package_inputs';

    protected $fillable = [
        'package_id',
        'parent',
        'label',
        'input_key',
        'input_type',
        'options',
        'placeholder',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    /**
     * Relationship to the Package model (optional if exists)
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
