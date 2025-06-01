<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenses = DB::table('expenses')
            ->join('entities', 'expenses.entity_id', '=', 'entities.id')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->select(
                'expenses.*',
                'entities.name as entity_name',
                'categories.name as category_name'
            )
            ->get();

        return response()->json([
            'expenses' => $expenses,
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
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'required|max:255',
            'amount' => 'required|numeric'
        ]);

        try {
            Expense::create($validated);
            return response()->json([
                'message' => 'Gasto registrado exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el gasto. '.$e->getMessage(),
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
            $expense = DB::table('expenses')
                ->join('entities', 'expenses.entity_id', '=', 'entities.id')
                ->join('categories', 'expenses.category_id', '=', 'categories.id')
                ->select(
                    'expenses.*',
                    'entities.name as entity_name',
                    'categories.name as category_name'
                )
                ->where('expenses.id', $id)
                ->first();

            if (!$expense) {
                return response()->json([
                    'message' => 'Gasto no encontrado.',
                    'error' => true
                ], 404);
            }

            return response()->json([
                'expense' => $expense,
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el gasto.',
                'error' => true
            ], 500);
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
            'category_id' => 'sometimes|integer|exists:categories,id',
            'description' => 'sometimes|max:255',
            'amount' => 'sometimes|numeric'
        ]);

        if (empty($validated)) {
            return response()->json([
                'message' => 'No se proporcionaron datos para actualizar.',
                'error' => true
            ], 400);
        }

        try {
            $expense = Expense::findOrFail($id);
            $expense->update($validated);
            return response()->json([
                'message' => 'Gasto actualizado exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el gasto.',
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
            $entity = Expense::findOrFail($id);
            $entity->update(['status' => $validated['status']]);
            return response()->json([
                'message' => 'Estado del gasto actualizado exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el estado del gasto.',
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
            $expense = Expense::findOrFail($id);
            $expense->delete();
            return response()->json([
                'message' => 'Gasto eliminado exitosamente.',
                'error' => false
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el gasto.',
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
            'expenses' => "required|array|max:$max",
            'expenses.*.datetime' => 'required|date',
            'expenses.*.entity_id' => 'required|integer|exists:entities,id',
            'expenses.*.category_id' => 'required|integer|exists:categories,id',
            'expenses.*.description' => 'required|max:255',
            'expenses.*.amount' => 'required|numeric'
        ]);

        try {
            foreach ($validated['expenses'] as $expenseData) {
                Expense::create($expenseData);
            }
            return response()->json([
                'message' => 'Gastos registrados exitosamente.',
                'error' => false
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar los gastos: ' . $e->getMessage(),
                'error' => true
            ], 500);
        }
    }
}
