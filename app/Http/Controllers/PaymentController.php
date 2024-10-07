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
            'discount_codes' => 'nullable|array', // Aquí permitimos un array de códigos de descuento
            'discount_codes.*' => 'string', // Validamos que cada código sea una cadena
        ]);

        // Verificar si hay capacidad disponible
        if ($event->capacity <= 0) {
            return response()->json(['message' => 'Capacidad agotada, te hemos añadido a la lista de espera.'], 400);
        }

        $basePrice = $event->base_price;
        $additionalPrice = 0;

        // Calcular precio adicional basado en el tipo de boleto
        if ($validatedData['ticket_type'] === 'general') {
            $additionalPrice = $basePrice * 0.15;
        } elseif ($validatedData['ticket_type'] === 'vip') {
            $additionalPrice = $basePrice * 0.30;
        }

        $totalPrice = $basePrice + $additionalPrice;

        // Aplicar códigos de descuento si existen
        if (!empty($validatedData['discount_codes'])) {
            $totalDiscount = 0;

            foreach ($validatedData['discount_codes'] as $code) {
                $discount = $this->getDiscountByCode($code); // Usamos el método que implementaremos más abajo
                $totalDiscount += $discount; // Acumulamos los descuentos
            }

            // Asegurarse de que el descuento no supere el 100%
            $totalDiscount = min($totalDiscount, 100);

            // Aplicamos el descuento acumulado al precio total
            $totalPrice -= $totalPrice * ($totalDiscount / 100);

            // Asegurarse que el precio final no sea menor al 70% del valor base
            $totalPrice = max($totalPrice, $basePrice * 0.70);
        }

        // Crear el boleto
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'attendee_id' => $validatedData['attendee_id'],
            'ticket_type' => $validatedData['ticket_type'],
            'price' => $totalPrice,
            'discount_code' => implode(',', $validatedData['discount_codes'] ?? []), // Guardar los códigos de descuento como una cadena separada por comas
            'purchase_date' => now(),
        ]);

        // Disminuir capacidad del evento
        $event->capacity -= 1;
        $event->save();

        return response()->json(['message' => 'Compra realizada con éxito', 'ticket' => $ticket]);
    }

    // Método para obtener los detalles de compra
    public function getPurchases()
    {
        // Recuperar los boletos con las relaciones del evento y el asistente
        $tickets = Ticket::with(['event', 'attendee'])->get();

        return response()->json($tickets);
    }

    // Método para obtener un descuento basado en el código de la tabla discount_codes
    protected function getDiscountByCode($code)
    {
        // Buscar el descuento en la base de datos usando el modelo DiscountCode
        $discountCode = DiscountCode::where('code', $code)
            ->where('valid_from', '<=', now()) // Asegurarse de que esté dentro de las fechas de validez
            ->where('valid_until', '>=', now())
            ->where('status', 1) // Verificar que el descuento esté activo
            ->first();

        // Retornar el porcentaje de descuento o 0 si no existe
        return $discountCode ? $discountCode->discount_percentage : 0;
    }
}
