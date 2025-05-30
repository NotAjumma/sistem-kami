<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingTicket extends Model
{
    protected $table = 'booking_tickets';

    protected $fillable = [
        'booking_id',
        'ticket_id',
        'participant_name',
        'participant_email',
        'participant_no_ic',
        'checked_in_at',
        'status',
    ];

    protected $dates = [
        'checked_in_at',
    ];

    // Relasi ke booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi ke ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
