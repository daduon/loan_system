<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'customer_type_name' => $this->customer_type_name,
            'customer_type_des' => $this->customer_type_des,
            'customer_type_rate' => $this->customer_type_rate,
            'customer_type_status' => $this->customer_type_status,
        ];
    }
}
