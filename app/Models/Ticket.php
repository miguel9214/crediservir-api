<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'ticket_type',
        'price',
        'discount_code',
        'purchase_date'
    ];

    // Relación con el evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Relación con el asistente
    public function attendee()
    {
        return $this->belongsTo(Attendee::class);
    }

    public function discountCodes()
    {
        return $this->belongsToMany(DiscountCode::class, 'discount_ticket');
    }
}
