<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishingRank extends Model
{
    protected $fillable = [
        'name',
        'type',
        'target_weight',
        'time_mode',
        'calculation_mode', // 'calculation_mode', ['single', 'total']
        'sub_type',         // 'sub_type', ['heaviest', 'most_catch', 'other']
    ];

    public function leaderboards()
    {
        return $this->hasMany(FishingLeaderboard::class);
    }
}
