<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'no_ic',
        'country',
        'state',
        'city',
        'postcode',
        'address',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
