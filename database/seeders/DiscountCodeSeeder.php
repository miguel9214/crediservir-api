<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DiscountCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiscountCode::create([
            'code' => 'DESCUENTO10',
            'discount_percentage' => 10.00,
            'valid_from' => Carbon::create(2024, 9, 1), // Añadido
            'valid_until' => '2024-11-30',
            'status' => 1, // Añadido
        ]);

        DiscountCode::create([
            'code' => 'DESCUENTO20',
            'discount_percentage' => 20.00,
            'valid_from' => Carbon::create(2024, 10, 1), // Añadido
            'valid_until' => '2024-12-31',
            'status' => 1, // Añadido
        ]);

        DiscountCode::create([
            'code' => 'DESCUENTO30',
            'discount_percentage' => 30.00,
            'valid_from' => Carbon::create(2024, 8, 15), // Añadido
            'valid_until' => '2024-10-15',
            'status' => 0, // Inactivo
        ]);
    }
}
