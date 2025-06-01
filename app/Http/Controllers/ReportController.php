<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function balance(Request $request)
    {
        // Aplica los mismos filtros a incomes y expenses
        $incomesQuery = DB::table('incomes')->whereIn('status', ['Activo']);
        $expensesQuery = DB::table('expenses')->whereIn('status', ['Activo']);

        // Filtros (ejemplo para fechas)
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $incomesQuery->whereBetween('datetime', [$request->date_from, $request->date_to]);
            $expensesQuery->whereBetween('datetime', [$request->date_from, $request->date_to]);
        }
        // ...otros filtros similares...

        $totalIncomes = $incomesQuery->sum('amount');
        $totalExpenses = $expensesQuery->sum('amount');
        $balance = $totalIncomes - $totalExpenses;

        return response()->json([
            'total_incomes' => $totalIncomes,
            'total_expenses' => $totalExpenses,
            'balance' => $balance,
            'error' => false
        ]);
    }
}
