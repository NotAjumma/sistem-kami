<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishingRank extends Model
{
    protected $fillable = [
        'name',
        'type',
        'sub_type',
        'target_weight',
        'target_weight_limit',
        'calculation_mode',
        'time_mode',
    ];

    public function leaderboards()
    {
        return $this->hasMany(FishingLeaderboard::class);
    }
}
