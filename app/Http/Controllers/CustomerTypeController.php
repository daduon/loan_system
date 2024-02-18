<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerManagement\StoreCustomerTypeRequest;
use App\Http\Requests\CustomerManagement\UpdateCustomerTypeRequest;
use App\Http\Resources\CustomerTypeResource;
use App\Models\CustomerType;
use Exception;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customerTypes = CustomerType::orderBy('id')->where('customer_type_status', 1)->get();

            return CustomerTypeResource::collection($customerTypes);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerTypeRequest $request)
    {
        try {
            $data = $request->all();
            $data['customer_type_created_by'] = Auth::user()->id;
            $data['customer_type_updated_by'] = Auth::user()->id;
            $customerType = CustomerType::create($data);
            $newCustomerType = CustomerType::whereId($customerType->id)->first();
            return new CustomerTypeResource($newCustomerType);
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
            $customerType = CustomerType::whereId($id)->where('customer_type_status',1)->first();

            if (!$customerType) {
                return response([
                    'message' => 'CustomerType not found to display',
                ], Response::HTTP_NOT_FOUND);
            }

            return new CustomerTypeResource($customerType);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerTypeRequest $request, $id)
    {
        try {
            $data = $request->all();
            if (($id === "1")) {
                return response([
                    'message' => 'General Type is not allowed to update',
                ]);
            } else {
                $data['customer_type_updated_by'] = Auth::user()->id;

                $customerType = CustomerType::whereId($id)->where('customer_type_status',1)->first();
                if (!$customerType) {
                    return response([
                    'message' => 'CustomerType not found to update',
                ], Response::HTTP_NOT_FOUND);
            }

            $customerType->update($data);

            return new CustomerTypeResource($customerType);
            }

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
        try {
            $customerType = CustomerType::whereId($id)->where('customer_type_status',1)->first();

            if (!$customerType) {
                return response([
                    'message' => 'CustomerType not found to delete',
                ],Response::HTTP_NOT_FOUND);
            }

            $customerType->update([
                'customer_type_status' => 0,
                'customer_type_updated_by' => Auth::user()->id,
            ]);

            return response([
                'message' => 'CustomerType deleted successfully',
            ]);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }
}
