<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketInputDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_input_id',
        'ticket_id',
        'booking_id',
        'answer',
    ];

    protected $casts = [
        'answer' => 'array',
    ];

    public function input()
    {
        return $this->belongsTo(TicketInput::class, 'ticket_input_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class); // or Order::class if you're using "orders"
    }
}
