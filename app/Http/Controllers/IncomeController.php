<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Income;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('incomes')
            ->join('entities', 'incomes.entity_id', '=', 'entities.id')
            ->whereIn('incomes.status', ['Activo', 'Inactivo'])
            ->select(
                'incomes.*',
                'entities.name as entity_name'
            );

        // Filtro por rango de fechas
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('incomes.datetime', [$request->date_from, $request->date_to]);
        }

        // Filtro por entidades (puede ser un array de IDs)
        if ($request->filled('entity_ids')) {
            $entityIds = is_array($request->entity_ids) ? $request->entity_ids : explode(',', $request->entity_ids);
            $query->whereIn('incomes.entity_id', $entityIds);
        }

        // Filtro por motivo (LIKE)
        if ($request->filled('reason')) {
            $query->where('incomes.reason', 'like', '%' . $request->reason . '%');
        }

        // Filtro por rango de montos
        if ($request->filled('amount_from') && $request->filled('amount_to')) {
            $query->whereBetween('incomes.amount', [$request->amount_from, $request->amount_to]);
        }

        $incomes = $query->orderBy('incomes.datetime', 'desc')->get();
        $total = $incomes->sum('amount');
        $average = $incomes->avg('amount');

        return response()->json([
            'incomes' => $incomes,
            'total' => $total,
            'average' => $average,
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
            'datetime' => 'required|date',
            'entity_id' => 'required|integer|exists:entities,id',
            'reason' => 'required|max:255',
            'amount' => 'required|numeric'
        ]);

        try {
            Income::create($validated);
            return response()->json([
                'message' => 'Ingreso registrado exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el ingreso.',
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
            $income = Income::findOrFail($id);
            return response()->json([
                'income' => $income,
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ingreso no encontrado.',
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
            'datetime' => 'sometimes|date',
            'entity_id' => 'sometimes|integer|exists:entities,id',
            'reason' => 'sometimes|max:255',
            'amount' => 'sometimes|numeric'
        ]);

        if (empty($validated)) {
            return response()->json([
                'message' => 'No se proporcionaron datos para actualizar.',
                'error' => true
            ], 400);
        }

        try {
            $income = Income::findOrFail($id);
            $income->update($validated);
            return response()->json([
                'message' => 'Ingreso actualizado exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el ingreso.',
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
            $income = Income::findOrFail($id);
            $income->update(['status' => $validated['status']]);
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
            $income = Income::findOrFail($id);
            $income->delete();
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

    public function storeMany(Request $request)
    {
        $max = 500; // Máximo de ingresos permitidos por petición

        $validated = $request->validate([
            'incomes' => "required|array|max:$max",
            'incomes.*.datetime' => 'required|date',
            'incomes.*.entity_id' => 'required|integer|exists:entities,id',
            'incomes.*.reason' => 'required|max:255',
            'incomes.*.amount' => 'required|numeric'
        ]);

        try {
            foreach ($validated['incomes'] as $incomeData) {
                Income::create($incomeData);
            }
            return response()->json([
                'message' => 'Ingresos registrados exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar los ingresos.',
                'error' => true
            ], 500);
        }
    }
}
