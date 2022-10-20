<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'payment_limit' => $this->payment_limit,
            'payment_count' => $this->payment_count,
            'payment_status' => $this->payment_status,
            'currency_id' => $this->currency_id
        ];
    }
}
