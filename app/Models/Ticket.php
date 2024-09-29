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
}
