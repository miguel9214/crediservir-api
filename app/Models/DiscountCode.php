<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percentage',
        'valid_from',
        'valid_until',
        'status',
    ];

    // Verificar si el código está activo y dentro de su rango de validez
    public function isValid()
    {
        return $this->status &&
            now()->between($this->valid_from, $this->valid_until);
    }


    public function getStatusAttribute()
    {
        return $this->attributes['status'] == 1 ? 'Activo' : 'Inactivo';
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'discount_ticket');
    }

    
}
