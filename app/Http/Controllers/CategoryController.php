<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::whereIn('status', ['Activo', 'Inactivo'])->get();
        return response()->json([
            'categories' => $categories,
            'error' => false
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255'
        ]);

        try {
            Category::create($validated);
            return response()->json([
                'message' => 'Categoría creada exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la categoría.',
                'error' => true
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'category' => $category,
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Categoría no encontrada.',
                'error' => true
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|max:255',
            'description' => 'sometimes|max:255'
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->update($validated);
            return response()->json([
                'message' => 'Categoría actualizada exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la categoría.',
                'error' => true
            ], 500);
        }
    }


    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Activo,Inactivo,Eliminado'
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->update(['status' => $validated['status']]);
            return response()->json([
                'message' => 'Estado de la categoría actualizado exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el estado de la categoría.',
                'error' => true
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json([
                'message' => 'Categoría eliminada exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la categoría.',
                'error' => true
            ], 500);
        }
    }

    /**
     * Store multiple newly created resources in storage.
     */
    public function storeMany(Request $request)
    {
        $max = 500; // Máximo de registros permitidos

        $validated = $request->validate([
            'categories' => "required|array|max:$max",
            'categories.*.name' => 'required|max:255',
            'categories.*.description' => 'required|max:255'
        ]);

        try {
            foreach ($validated['categories'] as $data) {
                Category::create($data);
            }
            return response()->json([
                'message' => 'Categorías registradas exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar las categorías.',
                'error' => true
            ], 500);
        }
    }
}
