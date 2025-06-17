<?php

// app/Models/BusinessGallery.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessGallery extends Model
{
    protected $table = 'business_gallery';

    protected $fillable = [
        'organizer_id',
        'file_name',
        'alt_text',
        'is_cover',
        'sort_order',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    // Image URL accessor
    public function getUrlAttribute()
    {
        return asset("images/organizers/{$this->organizer_id}/gallery/{$this->file_name}");
    }
}
