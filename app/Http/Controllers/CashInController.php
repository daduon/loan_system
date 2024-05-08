<?php

namespace App\Http\Controllers;

use App\Enums\StatusCode;
use App\Models\CashIn;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class CashInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cashIn = CashIn::orderBy('cash_in_date')->get();
            return response([
                'data' => $cashIn
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
            return CashIn::create([
                'cash_in_user_id' => $request->cash_in_user_id,
                'cash_in_amt_usd' => $request->cash_in_amt_usd,
                'cash_in_amt_khr' => $request->cash_in_amt_khr,
                'income_cash_in_usd' => $request->income_cash_in_usd,
                'income_cash_in_kh' => $request->income_cash_in_kh,
                'cash_in_date' => Carbon::now()->format('Ymd'),
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
    public function show(CashIn $cashIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashIn $cashIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashIn $cashIn)
    {
        //
    }
}
