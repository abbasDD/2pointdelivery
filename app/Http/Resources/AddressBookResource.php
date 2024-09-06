<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pickup_address' => $this->pickup_address,
            'dropoff_address' => $this->dropoff_address,
            'pickup_latitude' => $this->pickup_latitude,
            'pickup_longitude' => $this->pickup_longitude,
            'dropoff_latitude' => $this->dropoff_latitude,
            'dropoff_longitude' => $this->dropoff_longitude,
            'receiver_name' => $this->receiver_name,
            'receiver_phone' => $this->receiver_phone,
            'receiver_email' => $this->receiver_email
        ];
    }
}
