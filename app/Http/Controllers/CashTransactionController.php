<?php

namespace App\Http\Controllers;

use App\Enums\StatusCode;
use App\Models\CashTransaction;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class CashTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cashTransaction = CashTransaction::orderBy('date')->get();
            return response([
                'data' => $cashTransaction
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
        try {
            return CashTransaction::create([
                'cash_total_usd' => $request->cash_total_usd,
                'cash_total_kh' => $request->cash_total_kh,
                'date' => $request->date,
                'status' => StatusCode::ACTIVE->value,
                'cash_in_desc' => $request->cash_in_desc
            ]);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CashTransaction $cashTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashTransaction $cashTransaction)
    {
        try {
            $cashTransaction = CashTransaction::find($cashTransaction);
            $cashTransaction->cash_total_usd = $request->input('cash_total_usd');
            $cashTransaction->cash_total_kh = $request->input('cash_total_kh');
            $cashTransaction->date = $request->input('date');
            $cashTransaction->status = $request->input('status');
            $cashTransaction->cash_in_desc = $request->input('cash_in_desc');
            $cashTransaction->update();

            return $cashTransaction;
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashTransaction $cashTransaction)
    {
        //
    }
}
