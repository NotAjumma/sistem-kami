<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public $timestamps = true;

    /**
     * Relationship: A category has many events
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
