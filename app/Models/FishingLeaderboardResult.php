<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishingLeaderboardResult extends Model
{
    protected $fillable = [
        'fishing_leaderboard_id',
        'participant_id',
        'total_weight',
        'difference',
        'rank',
        'caught_at'
    ];

    protected $casts = [
        'caught_at' => 'datetime',
    ];

    // Leaderboard this result belongs to
    public function leaderboard()
    {
        return $this->belongsTo(FishingLeaderboard::class, 'fishing_leaderboard_id');
    }

    // Participant who owns this result
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
