<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\COEmployees\StoreCOEmployeesRequest;
use App\Http\Requests\COEmployees\UpdateCOEmployeesRequest;
use App\Http\Resources\COEmployeeResource;
use App\Models\COEmployee;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class COEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $coemployees = COEmployee::orderBy('id')->where('status', 1)->get();

            return COEmployeeResource::collection($coemployees);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCOEmployeesRequest $request)
    {
        try {
            $data = $request->all();
            $userId = Auth::user()->id;
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            $coemployee = COEmployee::create($data);
            $newCOEmployee = COEmployee::whereId($coemployee->id)->first();
            return new COEmployeeResource($coemployee);
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
            $coemployee = COEmployee::whereId($id)->first();
            return new COEmployeeResource($coemployee);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCOEmployeesRequest $request, $id)
    {
        try {
            $data = $request->all();
            $coemployee = COEmployee::whereId($id)->first();
            if (!$coemployee) {
                return response([
                    'message' => 'CO Employee not found',
                ]);
            }
            $data['updated_by'] = Auth::user()->id;
            $coemployee->update($data);
            return new COEmployeeResource($coemployee);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $coemployee = COEmployee::whereId($id)->first();

            if (!$coemployee) {
                return response([
                    'message' => 'Customer not found to delete',
                ], Response::HTTP_NOT_FOUND);
            }

            $coemployee->update([
                'status' => 0,
                'updated_by' => Auth::user()->id,
            ]);

            return response([
                'message' => 'Customer deleted successfully',
            ]);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
