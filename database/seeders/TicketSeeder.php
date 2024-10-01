<?php

namespace Database\Seeders;


use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ticket::create([
            'event_id' => 1, // ID del evento (Conferencia de Laravel)
            'attendee_id' => 1, // ID del asistente (Miguel Gómez)
            'ticket_type' => 'VIP',
            'price' => 150000.00,
            'purchase_date' => now(), // Base price 50 + 30% for VIP
        ]);

        Ticket::create([
            'event_id' => 2, // ID del evento (Taller de Vue.js)
            'attendee_id' => 2, // ID del asistente (Ana Martínez)
            'ticket_type' => 'General',
            'price' => 0.00, // Evento gratuito
            'purchase_date' => now(),
        ]);
    }
}
