<?php

namespace App\Http\Controllers;

use App\Enums\StatusCode;
use App\Models\Expense;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $expenses = Expense::orderBy('expense_date')->get();
            return response([
                'data' => $expenses
            ]);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $dateCreated = Carbon::now()->format('Ymd');
            $expenseNo = Carbon::now()->format('YmdHms');
            return Expense::create([
                'expense_no' => $expenseNo,
                'expense_desc' => $request->expense_desc,
                'expense_date' => $dateCreated,
                'expense_by' => $request->expense_by,
                'expense_amount_usd' => $request->expense_amount_usd,
                'expense_amount_kh' => $request->expense_amount_kh
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
