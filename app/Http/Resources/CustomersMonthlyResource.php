<?php

namespace App\Http\Resources;

use App\Models\Borrow_Master;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomersMonthlyResource extends JsonResource
{

    public function __construct($resource, $index)
    {
        parent::__construct($resource);
        $this->index = $index;
    }
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
            'No' => $this->index + 1,
            'customer_id' => $customerId ? $customerId->customer_id ?? "" : "",
            'customer_name' => $customerName ? $customerName->customer_name ?? "" : "",
            'due_date' => $this->paymenttodate,
            'principal' => $this->repayprincipal,
            'taxamount' => $this->taxamount,
            'repayinterest' => $this->repayinterest,
            'payment' => number_format($this->repayprincipal + $this->taxamount + $this->repayinterest, 2),
        ];
    }
}
