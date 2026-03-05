<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoterCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'commission_type',
        'commission_value',
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}
