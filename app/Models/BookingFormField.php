<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'field_key',
        'field_label',
        'field_type',
        'is_required',
        'options',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array', // auto json decode
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}