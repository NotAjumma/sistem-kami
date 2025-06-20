<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorOffDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'off_date',
        'reason',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}
