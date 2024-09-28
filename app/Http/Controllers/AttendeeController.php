<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    // Listar todos los asistentes
    public function index()
    {
        $attendees = Attendee::all();
        return response()->json($attendees);
    }

    // Guardar un nuevo asistente
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'required|email|unique:attendees,email',
            'phone' => 'required|string|max:20',
        ]);

        $attendee = Attendee::create($validatedData);
        return response()->json(['message' => 'Asistente registrado con éxito', 'attendee' => $attendee], 201);
    }

    // Mostrar un asistente específico
    public function show($id)
    {
        $attendee = Attendee::findOrFail($id);
        return response()->json($attendee);
    }

    // Actualizar información de un asistente
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'email' => 'required|email|unique:attendees,email,'.$id,
            'phone' => 'required|string|max:20',
        ]);

        $attendee = Attendee::findOrFail($id);
        $attendee->update($validatedData);
        return response()->json(['message' => 'Asistente actualizado con éxito', 'attendee' => $attendee]);
    }

    // Eliminar asistente
    public function destroy($id)
    {
        $attendee = Attendee::findOrFail($id);
        $attendee->delete();
        return response()->json(['message' => 'Asistente eliminado con éxito']);
    }
}
