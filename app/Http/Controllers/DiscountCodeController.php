<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiscountCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DiscountCodeController extends Controller
{
    // Listar todos los códigos de descuento
    public function index()
    {
        info("usuario", [Auth::user()]);
        $codes = DiscountCode::all();
        return response()->json($codes);
    }

    // Crear un nuevo código de descuento
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discount_codes',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'status' => 'required|boolean',
        ]);

        $validated["created_by_user"] = Auth::id();
        $discountCode = DiscountCode::create($validated);

        return response()->json(['message' => 'Código de descuento creado', 'discount' => $discountCode], 201);
    }

    // Validar un código de descuento
    public function validateCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $discountCode = DiscountCode::where('code', $request->code)->first();

        if (!$discountCode || !$discountCode->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Código inválido o vencido'], 400);
        }

        return response()->json([
            'valid' => true,
            'percentage' => $discountCode->discount_percentage,
            'message' => 'Código válido',
        ]);
    }

    // Actualizar un código de descuento
    public function update(Request $request, $id)
    {
        $discountCode = DiscountCode::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:discount_codes,code,' . $id,
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'status' => 'required|boolean',
        ]);

        $validated["updated_by_user"] = Auth::id();
        $discountCode->update($validated);

        return response()->json(['message' => 'Código de descuento actualizado', 'discount' => $discountCode]);
    }

    // Eliminar un código de descuento
    public function destroy($id)
    {
        $discountCode = DiscountCode::findOrFail($id);
        $discountCode->delete();

        return response()->json(['message' => 'Código de descuento eliminado']);
    }
}
