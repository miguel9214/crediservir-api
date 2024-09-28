<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', // Título del evento
        'description', // Descripción del evento
        'date', // Fecha y hora del evento
        'location', // Ubicación del evento
        'capacity', // Capacidad máxima
        'type', // Tipo de evento (gratis o pago)
        'base_price',
        'category_id', // Precio base del evento
        'status', // Estado del evento (abierto/cerrado)
        'registration_open', // Fecha de apertura de inscripciones
        'registration_close', // Fecha de cierre de inscripciones
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
