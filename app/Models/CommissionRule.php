<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'worker_id',
        'package_id',
        'addon_id',
        'commission_type',
        'commission_value',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function addon()
    {
        return $this->belongsTo(PackageAddon::class, 'addon_id');
    }
}
