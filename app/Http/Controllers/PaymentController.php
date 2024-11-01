<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\DiscountCode;
use App\Models\TicketDiscount;
use App\Models\WaitingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'discount_codes' => 'nullable'
        ]);

        info("datos", [$validatedData]);

        // Verificar si hay capacidad disponible
        if ($event->capacity <= 0) {

            $waiting = WaitingList::create([
                'event_id' => $event->id,
                'attendee_id' => $validatedData['attendee_id'],
            ]);
            return response()->json(['message' => 'Capacidad agotada, te hemos añadido a la lista de espera.', 'Asistente agregado a lista de espera' => $waiting], 400);
        }


        $basePrice = $event->base_price;
        $additionalPrice = 0;

        // $ticketType = TicketType::where('name', $validatedData['ticket_type'])->first();


        // Calcular precio adicional basado en el tipo de boleto
        if ($validatedData['ticket_type'] === 'general') {
            $additionalPrice = $basePrice * 0.15;
        } elseif ($validatedData['ticket_type'] === 'vip') {
            $additionalPrice = $basePrice * 0.30;
        }

        $totalPrice = $basePrice + $additionalPrice;
        $totalPriceBase = $totalPrice;

        // Aplicar códigos de descuento si existen
        if (count($validatedData['discount_codes']) > 0) {
            $totalDiscount = 0;

            foreach ($validatedData['discount_codes'] as $code) {
                $discount = $code["discount_percentage"] ?? 0; // Usamos el método que implementaremos más abajo
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
            // 'discount_code' => implode(',', $validatedData['discount_codes'] ?? []), // Guardar los códigos de descuento como una cadena separada por comas
            'purchase_date' => now(),
            'created_by_user' => Auth::id()
        ]);

        foreach ($validatedData['discount_codes'] as $key => $value) {
            $percentage = $value["discount_percentage"];
            $amount = $totalPriceBase * ($percentage / 100);
            TicketDiscount::create([
                'amount' => $amount,
                'percentage' => $percentage,
                'ticket_id' => $ticket->id,
                'discount_id' => $value["id"]
            ]);
        }

        // Disminuir capacidad del evento
        $event->capacity -= 1;
        $event->save();

        return response()->json(['message' => 'Compra realizada con éxito', 'ticket' => $ticket]);
    }

    // Método para obtener los detalles de compra
    public function getPurchases()
    {
        $tickets = Ticket::join('events', 'tickets.event_id', '=', 'events.id')
        ->join('attendees', 'tickets.attendee_id', '=', 'attendees.id')
        ->select(
        'tickets.id',
        'events.title',
        'events.description',
        'events.location',
        'events.date',
        'attendees.first_name',
        'attendees.last_name',
        'attendees.email',
        'attendees.phone',
        'tickets.price',
        'tickets.ticket_type',
        'tickets.purchase_date',
        )
        ->get();

            foreach ($tickets as $ticket) {
                $ticket->discount_code = TicketDiscount::join('discount_codes', 'ticket_discounts.discount_id', '=', 'discount_codes.id')
                   ->where('ticket_id', $ticket->id)
                    ->select('discount_codes.code', 'ticket_discounts.amount', 'ticket_discounts.percentage')
                    ->get();
            }

        return response()->json($tickets);
    }

    public function getDiscount()
    {

        $tickets = Ticket::join('events', 'tickets.event_id', '=', 'events.id')
        ->join('attendees', 'tickets.attendee_id', '=', 'attendees.id')
        ->select(
        'tickets.id',
        'events.title',
        'events.description',
        'events.location',
        'events.date',
        'attendees.first_name',
        'attendees.email',
        'attendees.phone',
        'tickets.price',
        'tickets.purchase_date',
        )
        ->get();

            foreach ($tickets as $ticket) {
                $ticket->discount_code = TicketDiscount::join('discount_codes', 'ticket_discounts.discount_id', '=', 'discount_codes.id')
                   ->where('ticket_id', $ticket->id)
                    ->select('discount_codes.code', 'ticket_discounts.amount', 'ticket_discounts.percentage')
                    ->get();
            }

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
