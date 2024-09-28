<?php

namespace Database\Seeders;

use App\Models\Attendee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attendee::create([
            'first_name' => 'Miguel',
            'last_name' => 'Gómez',
            'birth_date' => '1990-01-15',
            'email' => 'miguel@example.com',
            'phone' => '123456789',
        ]);

        Attendee::create([
            'first_name' => 'Ana',
            'last_name' => 'Martínez',
            'birth_date' => '1985-06-22',
            'email' => 'ana@example.com',
            'phone' => '987654321',
        ]);
    }
}
