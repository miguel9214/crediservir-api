<?php

namespace App\Http\Controllers;


use App\Models\WaitingList;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        // Recuperar la lista con las relaciones del evento y el asistente
        $waiting = WaitingList::with(['event', 'attendee'])->get();

        return response()->json($waiting);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'attendee_id' => 'required|exists:attendees,id',
            'event_id' => 'required|exists:attendees,id',
        ]);

        $waitList = WaitingList::create($validatedData);
        return response()->json(['message' => 'Se agregado asistente a lista de espera ', 'waiting' => $waitList], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $waitList = WaitingList::findOrFail($id);
        return response()->json($waitList);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'attendee_id' => 'required|exists:attendees,id',
            'event_id' => 'required|exists:attendees,id',
        ]);

        $category = WaitingList::findOrFail($id);
        $category->update($validatedData);
        return response()->json(['message' => 'Registro en lista de espera actualizado', 'waiting' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    // Eliminar categoría
    public function destroy($id)
    {
        $category = WaitingList::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Registro en ls lista de espera eliminado']);
    }
}
