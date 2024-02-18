<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHolidayDateRequest;
use App\Http\Requests\UpdateHolidayDateRequest;
use App\Http\Resources\HolidaDateResource;
use App\Models\HolidayDate;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class HolidayDateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $holidayDate = HolidayDate::orderBy('basedate')->get();
            return HolidaDateResource::collection($holidayDate);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHolidayDateRequest $request)
    {
        try {
            $current_date_time = Carbon::now();
            $year = $current_date_time->year;
            $month = $current_date_time->month;
            $day = $current_date_time->day;
            $formatted_date = sprintf("%04d%02d%02d", $year, $month, $day);

            $data = $request->all();
            $data["registerdate"] = $formatted_date;
            $data["modifydate"] = "";
            $holidayDate = HolidayDate::create($data);

            return new HolidaDateResource($holidayDate);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

        /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $holidayDate = HolidayDate::find($id);
            return new HolidaDateResource($holidayDate);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayDateRequest $request, $id)
    {
        try {
            $holidayDate = HolidayDate::find($id);
            $current_date_time = Carbon::now();
            $year = $current_date_time->year;
            $month = $current_date_time->month;
            $day = $current_date_time->day;
            $formatted_date = sprintf("%04d%02d%02d", $year, $month, $day);
            $data = $request->all();
            $data["modifydate"] = $formatted_date;
            $holidayDate->update($data);
            return new HolidaDateResource($holidayDate);

        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource from storage to inactive.
     */
    public function destroy($id)
    {
        // dd($id);
        try {
            $holidayDate = HolidayDate::find($id);
            $holidayDate->delete();
            return response([
                'message' => 'Holiday Date deleted successfully',
            ]);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
