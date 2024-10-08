<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'discount_id',
        'percentage',
        'amount',
    ];

}
