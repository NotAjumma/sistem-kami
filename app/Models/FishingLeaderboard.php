<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishingLeaderboard extends Model
{
    protected $fillable = [
        'name',
        'fishing_rank_id',
        'starts_at',
        'ends_at'
    ];

    public function rank()
    {
        return $this->belongsTo(FishingRank::class, 'fishing_rank_id');
    }

    public function catches()
    {
        return $this->hasManyThrough(FishingCatch::class, Participant::class, 'id', 'participant_id');
    }

    public function results()
    {
        return $this->hasMany(FishingLeaderboardResult::class);
    }
}
