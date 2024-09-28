<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Conferencia de Laravel',
            'description' => 'Un evento sobre el framework Laravel.',
            'date' => '2024-12-01 10:00:00',
            'location' => 'Auditorio Principal',
            'capacity' => 100,
            'type' => 'paid',
            'base_price' => 50.00,
            'category_id' => 1, // Conferencias
            'registration_open' => '2024-11-01 08:00:00',
            'registration_close' => '2024-11-30 23:59:59',
        ]);

        Event::create([
            'title' => 'Taller de Vue.js',
            'description' => 'Aprende a crear interfaces interactivas con Vue.js.',
            'date' => '2024-12-05 14:00:00',
            'location' => 'Sala de Conferencias B',
            'capacity' => 50,
            'type' => 'free',
            'base_price' => null, // No necesario para eventos gratuitos
            'category_id' => 2, // Talleres
            'registration_open' => '2024-11-05 08:00:00',
            'registration_close' => '2024-12-04 23:59:59',
        ]);
    }
}
