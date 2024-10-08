<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    // Listar todos los eventos
    public function index()
    {
        $events = Event::with('category')->get(); // Trae eventos con categorías
        return response()->json($events);
    }

    // Crear nuevo evento
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'type' => 'required|in:free,paid',
            'base_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'registration_open' => 'required|date',
            'registration_close' => 'required|date',
        ]);

        $validatedData["created_by_user"] = Auth::id();
        $event = Event::create($validatedData);
        return response()->json(['message' => 'Evento creado con éxito', 'event' => $event], 201);
    }

    // Actualizar evento existente
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'type' => 'required|in:free,paid',
            'base_price' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id',
            'registration_open' => 'required|date',
            'registration_close' => 'required|date',
        ]);

        $validatedData["updated_by_user"] = Auth::id();
        $event = Event::findOrFail($id);
        $event->update($validatedData);
        return response()->json(['message' => 'Evento actualizado con éxito', 'event' => $event]);
    }

    // Eliminar evento
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(['message' => 'Evento eliminado con éxito']);
    }
}
