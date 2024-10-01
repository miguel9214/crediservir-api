<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Mostrar detalles de un evento para realizar una compra
    public function showEvent($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }

    // Comprar boletos
    public function purchaseTicket(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $validatedData = $request->validate([
            'attendee_id' => 'required|exists:attendees,id',
            'ticket_type' => 'required|in:free,general,vip',
            'discount_code' => 'nullable|string', // Cambiar a string
        ]);

        // Verificar si hay capacidad disponible
        if ($event->capacity <= 0) {
            return response()->json(['message' => 'Capacidad agotada, te hemos añadido a la lista de espera.'], 400);
        }

        $basePrice = $event->base_price;
        $additionalPrice = 0;

        if ($validatedData['ticket_type'] === 'general') {
            $additionalPrice = $basePrice * 0.15;
        } elseif ($validatedData['ticket_type'] === 'vip') {
            $additionalPrice = $basePrice * 0.30;
        }

        $totalPrice = $basePrice + $additionalPrice;

        // Aplicar códigos de descuento si existen
        if ($validatedData['discount_code']) {
            // Dividir los códigos de descuento por coma
            $codes = explode(',', $validatedData['discount_code']);
            $totalDiscountPercentage = 0;

            foreach ($codes as $code) {
                $code = trim($code); // Limpiar espacios en blanco
                $discountCode = DiscountCode::where('code', $code)->first();

                if ($discountCode && $discountCode->status && now()->between($discountCode->valid_from, $discountCode->valid_until)) {
                    // Sumar el porcentaje de descuento
                    $totalDiscountPercentage += $discountCode->discount_percentage;
                } else {
                    return response()->json(['message' => "Código de descuento '$code' no válido"], 400);
                }
            }

            // Asegurarse que el total de descuento no exceda el 100%
            if ($totalDiscountPercentage > 100) {
                $totalDiscountPercentage = 100;
            }

            // Aplicar el descuento total
            $totalPrice -= $totalPrice * ($totalDiscountPercentage / 100);

            // Asegurarse que el valor no sea menor al 70% del valor base
            $totalPrice = max($totalPrice, $basePrice * 0.70);
        }

        // Crear el boleto
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'attendee_id' => $validatedData['attendee_id'],
            'ticket_type' => $validatedData['ticket_type'],
            'price' => $totalPrice,
            'discount_code' => $validatedData['discount_code'], // Almacenar los códigos de descuento como una cadena
            'purchase_date' => now(),
        ]);

        // Disminuir capacidad del evento
        $event->capacity -= 1;
        $event->save();

        return response()->json(['message' => 'Compra realizada con éxito', 'ticket' => $ticket]);
    }

    public function getPurchases()
    {
        // Recuperar los boletos con las relaciones del evento y el asistente
        $tickets = Ticket::with(['event', 'attendee'])->get();

        return response()->json($tickets);
    }
}
