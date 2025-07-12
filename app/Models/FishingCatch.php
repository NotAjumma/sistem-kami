<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishingCatch extends Model
{
    protected $fillable = [
        'participant_id',
        'fishing_leaderboard_id',
        'weight',
        'caught_at'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
