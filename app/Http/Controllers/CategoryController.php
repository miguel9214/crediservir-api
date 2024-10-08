<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated["created_by_user"] = Auth::id();
        $category = Category::create($validatedData);
        return response()->json(['message' => 'Categoría creada con éxito', 'category' => $category], 201);
    }

    // Mostrar una categoría específica
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Actualizar categoría existente
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validatedData["updated_by_user"] = Auth::id();
        $category = Category::findOrFail($id);
        $category->update($validatedData);
        return response()->json(['message' => 'Categoría actualizada con éxito', 'category' => $category]);
    }

    // Eliminar categoría
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Categoría eliminada con éxito']);
    }
}
