<?php

namespace App\Http\Resources;

use App\Models\Borrow_Master;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $userName = User::whereId($this->customer_created_by)->where('status',1)->first()->name;
        $isUpdateType = Borrow_Master::where('customer_id',$this->id)->where('ispaid',0)->first();
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_gender' => $this->customer_gender,
            'customer_picture' => $this->customer_picture,
            'customer_dob' => $this->customer_dob,
            'customer_address' => $this->customer_address,
            'customer_phone' => $this->customer_phone,
            'isdiscount' => $this->isdiscount,
            'customer_status' => $this->customer_status,
            'customer_identity' => $this->customer_identity,
            'isUpdateType' => $isUpdateType ? false : true,
            'customer_created_by' => $userName,
            'customer_updated_by'=> $userName,
            'customer_type_id' => CustomerType::whereId($this->customer_type_id)->where('customer_type_status',1)->first(['id','customer_type_name','customer_type_des','customer_type_rate']),
        ];
    }
}
