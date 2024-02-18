<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerManagement\StoreCustomerRequest;
use App\Http\Requests\CustomerManagement\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\CustomersMonthlyResource;
use App\Http\Resources\NotificationResource;
use App\Models\Borrow_Master;
use App\Models\Borrow_Schedule;
use App\Models\Customer;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $customers = Customer::orderBy('id')->where('customer_status', 1)->get();

            return CustomerResource::collection($customers);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getallcustomer()
    {
        try {
            $customers = DB::select("
                SELECT
                          C.ID
                        , CT.ID             AS cusTypeId
                        , C.CUSTOMER_NAME
                        , CT.CUSTOMER_TYPE_NAME
                        , CT.CUSTOMER_TYPE_RATE
                FROM CUSTOMERS AS C
                INNER JOIN CUSTOMER_TYPES AS CT ON C.CUSTOMER_TYPE_ID = CT.ID Where C.customer_status = '1'");
            return response()->json($customers);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            $data = $request->all();
            $data['customer_created_by'] = Auth::user()->id;
            $data['customer_updated_by'] = Auth::user()->id;

            // if ($request->hasFile('customer_picture')) {
            //     $image = $request->file('customer_picture');
            //     $data['customer_picture'] = $this->convertImageToBase64($image);
            // }

            $customer = Customer::create($data);
            $newCustomer = Customer::whereId($customer->id)->first();
            return new CustomerResource($newCustomer);
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
            $customer = Customer::whereId($id)->where('customer_status', 1)->first();

            if (!$customer) {
                return response([
                    'message' => 'Customer not found',
                ], Response::HTTP_NOT_FOUND);
            }

            return new CustomerResource($customer);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        try {
            $data = $request->except("customer_type_id");

            $customer = Customer::whereId($id)->where('customer_status', 1)->first();

            if (!$customer) {
                return response([
                    'message' => 'Customer not found to update',
                ]);
            }

            // if ($request->hasFile('customer_picture')) {
            //     $image = $request->file('customer_picture');
            //     $data['customer_picture'] = $this->convertImageToBase64($image);
            // }

            $data['customer_updated_by'] = Auth::user()->id;

            $customer->update($data);

            return new CustomerResource($customer);
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
            $customer = Customer::whereId($id)->where('customer_status', 1)->first();

            if (!$customer) {
                return response([
                    'message' => 'Customer not found to delete',
                ], Response::HTTP_NOT_FOUND);
            }

            $customer->update([
                'customer_status' => 0,
                'customer_updated_by' => Auth::user()->id,
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

    public function notifications()
    {
        try {
            $currentDate = Carbon::now();
            $alertDate = $currentDate->copy()->addDays(7);
            $notifications = Borrow_Schedule::orderBy('paymenttodate')->where('schedulestatuscode', 0)
                ->whereDate('paymenttodate', '<=', $alertDate->format('Y-m-d'))
                ->get();

            return NotificationResource::collection($notifications);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function customersMonthly($month)
    {
        try {
            $lists = Borrow_Schedule::orderBy('paymenttodate')
                ->where('schedulestatuscode', 0)
                ->where('paymenttodate', 'LIKE', $month . '%')
                ->get();
            return CustomersMonthlyResource::collection($lists);
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }

    private function convertImageToBase64($image)
    {
        $imageManager  = new ImageManager();
        $compressImage = $imageManager->make($image)->resize(200, 200);
        $base64Image = base64_encode($compressImage->encode('png', 70));

        return $base64Image;
    }
}
