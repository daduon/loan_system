<?php

namespace App\Http\Resources;

use App\Models\Borrow_Master;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $customerId = Borrow_Master::where('id',$this->borrowing_id)->first();
        $customerName = Customer::where('id', $customerId->customer_id)->first();

        return [
            'borrowing_id' => $this->borrowing_id,
            'customer_id' => $customerId ? $customerId->customer_id ?? "" : "",
            'customer_name' => $customerName ? $customerName->customer_name ?? "" : "",
            'paymenttodate' => $this->paymenttodate,
        ];
    }
}
