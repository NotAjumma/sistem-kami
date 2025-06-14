<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketInput extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'parent',
        'label',
        'label_key',
        'input_type',
        'options',
        'placeholder',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function details()
    {
        return $this->hasMany(TicketInputDetail::class);
    }
}
