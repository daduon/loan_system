<?php

namespace App\Http\Controllers;

use App\Enums\CashTransactionType;
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
            $cashIns = CashIn::orderBy('updated_at', 'desc')->get();
            $cashIns->transform(function ($cashIn) {
                $cashIn->cash_in_user_name = $cashIn->getUserName();
                return $cashIn;
            });
            return response([
                'data' => $cashIns
            ]);
        } catch (Exception $e) {
            return response([
                'message' => 'Something went wrong!',
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $descExisted = $request->cash_in_desc;
        DB::beginTransaction();
        try {
            $dateCreated = Carbon::now()->format('Ymd');
            $cashIn = new CashIn();
            $cashIn->cash_in_user_id = $request->cash_in_user_id;
            $cashIn->cash_in_amt_usd = $request->cash_in_amt_usd ?? 0;
            $cashIn->cash_in_amt_khr = $request->cash_in_amt_khr ?? 0;
            $cashIn->income_cash_in_usd = $request->income_cash_in_usd ?? 0;
            $cashIn->income_cash_in_kh = $request->income_cash_in_kh ?? 0;
            $cashIn->cash_in_date = $dateCreated;
            $cashIn->cash_in_desc = $request->cash_in_desc ?? CashTransactionType::LOAN->value;
            $cashIn->save();

            $amountUSD = $cashIn->cash_in_amt_usd + $cashIn->income_cash_in_usd;
            $amountKHR = $cashIn->cash_in_amt_khr + $cashIn->income_cash_in_kh;

            $cashinTotal = CashTransaction::get();

            if ($cashinTotal->isEmpty()) {
                $cashTrnscnt = new CashTransaction();
                $cashTrnscnt->cash_total_usd = $amountUSD;
                $cashTrnscnt->cash_total_kh = $amountKHR;
                $cashTrnscnt->date = $dateCreated;
                $cashTrnscnt->cash_in_desc = $cashIn->cash_in_desc;
                $cashTrnscnt->save();
            } else {
                $cashTrnscnt = CashTransaction::find($cashinTotal->get(0)->id);
                $cashTrnscnt->cash_total_usd += $amountUSD;
                $cashTrnscnt->cash_total_kh += $amountKHR;
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
