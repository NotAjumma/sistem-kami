<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorAction extends Model
{
    protected $fillable = [
        'visitor_id',
        'action',
        'page',
        'uri',
        'meta',
        'reference_id',
        'ip_address',
        'browser',
        'platform',
        'device',
        'user_agent',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

}
