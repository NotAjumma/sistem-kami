<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageInputAnswer extends Model
{
    use HasFactory;

    protected $table = 'package_input_answers';

    protected $fillable = [
        'package_input_id',
        'package_id',
        'booking_id',
        'answer',
    ];

    public function input()
    {
        return $this->belongsTo(PackageInput::class, 'package_input_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
