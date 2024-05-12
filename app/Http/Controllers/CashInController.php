<?php

namespace App\Http\Controllers;

use App\Enums\StatusCode;
use App\Models\CashIn;
use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $cashIns = CashIn::orderBy('cash_in_date')->get();
            $cashIns->transform(function ($cashIn) {
                $cashIn->cash_in_user_name = $cashIn->getUserName();
                return $cashIn;
            });
            return response([
                'data' => $cashIns
            ]);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ], 500);
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
            $cashIn = new CashIn();
            $cashIn->cash_in_user_id = $request->cash_in_user_id;
            $cashIn->cash_in_amt_usd = $request->cash_in_amt_usd;
            $cashIn->cash_in_amt_khr = $request->cash_in_amt_khr;
            $cashIn->income_cash_in_usd = $request->income_cash_in_usd;
            $cashIn->income_cash_in_kh = $request->income_cash_in_kh;
            $cashIn->cash_in_date = $dateCreated;
            $cashIn->cash_in_desc = $request->cash_in_desc;
            $cashIn->save();

            $cashinTotal = CashTransaction::get();

            if ($cashinTotal->isEmpty()) {
                $cashTrnscnt = new CashTransaction();
                $cashTrnscnt->cash_total_us = $cashIn->cash_in_amt_usd;
                $cashTrnscnt->cash_total_kh = $cashIn->cash_in_amt_khr;
                $cashTrnscnt->date = $dateCreated;
                $cashTrnscnt->cash_in_desc = $cashIn->cash_in_desc;
                $cashTrnscnt->save();
            } else {
                $cashTrnscnt = CashTransaction::find($cashinTotal->get(0)->id);
                $cashTrnscnt->cash_total_us += $cashIn->cash_in_amt_usd;
                $cashTrnscnt->cash_total_kh += $cashIn->cash_in_amt_khr;
                $cashTrnscnt->date = $dateCreated;
                $cashTrnscnt->cash_in_desc = $cashIn->cash_in_desc;
                $cashTrnscnt->save();
            }

            DB::commit();
            return $cashIn;
        } catch (Exception $e) {
            DB::rollBack();
            return response([
                'message' => 'Something went wrong while cashing in',
            ], 500);
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
