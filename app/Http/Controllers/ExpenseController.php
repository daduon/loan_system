<?php

namespace App\Http\Controllers;

use App\Enums\CashTransactionType;
use App\Models\Expense;
use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
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
            $expenses->transform(function ($expense) {
                $expense->expense_by = $expense->getUserName();
                return $expense;
            });
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

            $expense = new Expense();
            $expense->expense_no = $expenseNo;
            $expense->expense_desc = $request->expense_desc ?? CashTransactionType::LOAN->value;
            $expense->expense_date = $dateCreated;
            $expense->expense_by = $request->expense_by;
            $expense->expense_amount_usd = $request->expense_amount_usd;
            $expense->expense_amount_kh = $request->expense_amount_kh;
            $expense->save();

            $amountUSD = $expense->expense_amount_usd;
            $amountKHR = $expense->expense_amount_kh;

            $cashinTotal = CashTransaction::get();

            if ($cashinTotal->isEmpty()) {
                throw new Exception('Please insert some cash to make your transaction!', 500);
            } else {
                $cashTrnscnt = CashTransaction::find($cashinTotal->get(0)->id);
                $totalUSD = $cashTrnscnt->cash_total_usd;
                $totalKHR = $cashTrnscnt->cash_total_kh;
                $this->handleTransactionUSD($amountUSD, $totalUSD);
                $this->handleTransactionKHR($amountKHR, $totalKHR);

                $cashTrnscnt->update([
                    'cash_total_usd' => $totalUSD -= $amountUSD,
                    'cash_total_kh' => $totalKHR -= $amountKHR,
                    'date' => $dateCreated,
                    'cash_in_desc' => $expense->expense_desc,
                ]);
            }

            DB::commit();
            return $expense;
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

    private function handleTransactionUSD($amountUSD, $totalUSD)
    {
        if ($amountUSD > 0) {
            if ($totalUSD == 0) {
                throw new Exception('Please insert some cash USD to make your transaction!', 500);
            } else if ($totalUSD < $amountUSD) {
                throw new Exception('Your cash USD is not enough to make your transaction!', 500);
            }
        }
    }

    private function handleTransactionKHR($amountKHR, $totalKHR)
    {
        if ($amountKHR > 0) {
            if ($totalKHR == 0) {
                throw new Exception('Your cash USD is not enough to make your transaction!', 500);
            } else if ($totalKHR < $amountKHR) {
                throw new Exception('Your cash KHR is not enough to make your transaction!', 500);
            }
        }
    }
}
