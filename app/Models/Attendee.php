<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;


    public function attendees()
    {
        return $this->belongsToMany(Attendee::class, 'tickets')
            ->withPivot('ticket_type', 'price', 'discount_code', 'purchase_date')
            ->withTimestamps();
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'date',
        'birth_date',
        'email',
        'phone',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }


}
