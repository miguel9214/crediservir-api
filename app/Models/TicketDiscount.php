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

            // Relación con los tickets
            public function ticket()
            {
                return $this->belongsTo(Ticket::class);
            }

            // Relación con los descuentos
            public function discount()
            {
                return $this->belongsTo(DiscountCode::class);
            }
}
