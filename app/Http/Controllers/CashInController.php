<?php

namespace App\Http\Controllers;

use App\Models\CashIn;
use App\Http\Controllers\Controller;
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
        //
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
