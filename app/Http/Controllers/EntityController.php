<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Entity;

class EntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entities = Entity::whereIn('status', ['Activo', 'Inactivo'])->get();
        return response()->json([
            'entities' => $entities,
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
            'phone' => 'nullable|max:30',
            'email' => 'nullable|max:50',
            'address' => 'nullable|max:255'
        ]);

        try {
            Entity::create($validated);
            return response()->json([
                'message' => 'Entidad registrada exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar la entidad.',
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
            $entity = Entity::findOrFail($id);
            return response()->json([
                'entity' => $entity,
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Entidad no encontrada.',
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
            'name' => 'sometimes|nullable|max:255',
            'phone' => 'sometimes|nullable|max:30',
            'email' => 'sometimes|nullable|max:50',
            'address' => 'sometimes|nullable|max:255'
        ]);

        if (empty($validated)) {
            return response()->json([
                'message' => 'No se proporcionaron datos para actualizar.',
                'error' => true
            ], 400);
        }

        try {
            $entity = Entity::findOrFail($id);
            $entity->update($validated);
            return response()->json([
                'message' => 'Entidad actualizada exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la entidad.',
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
            $entity = Entity::findOrFail($id);
            $entity->update(['status' => $validated['status']]);
            return response()->json([
                'message' => 'Estado del ingreso actualizado exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el estado del ingreso.',
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
            $entity = entity::findOrFail($id);
            $entity->delete();
            return response()->json([
                'message' => 'Ingreso eliminado exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el ingreso.',
                'error' => true
            ], 500);
        }
    }

    /**
     * Store multiple newly created resources in storage.
     */
    public function storeMany(Request $request)
    {
        $max = 500; // Máximo de registros permitidos por petición

        $validated = $request->validate([
            'entities' => "required|array|max:$max",
            'entities.*.name' => 'required|max:255',
            'entities.*.phone' => 'nullable|max:30',
            'entities.*.email' => 'nullable|max:50',
            'entities.*.address' => 'nullable|max:255'
        ]);

        try {
            foreach ($validated['entities'] as $entityData) {
                Entity::create($entityData);
            }
            return response()->json([
                'message' => 'Entidades registradas exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar las entidades.',
                'error' => true
            ], 500);
        }
    }
}
