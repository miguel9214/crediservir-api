<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitingList extends Model
{
    use HasFactory;

    public function waitingList()
    {
        return $this->hasMany(WaitingList::class);
    }

    protected $fillable = [
        'event_id',
        'attendee_id',
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

}
