<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Country;
use App\Models\KycType;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KycDetailResource extends JsonResource
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
            'kyc_type_name' => KycType::find($this->kyc_type_id)->name ?? '-',
            'front_image' => $this->front_image ? asset('images/kyc/' . $this->front_image) : asset('images/default.png'),
            'back_image' => $this->back_image ? asset('images/kyc/' . $this->back_image) : asset('images/default.png'),
            'id_number' => $this->id_number,
            'country' => Country::find($this->country_id)->name ?? '-',
            'state' => State::find($this->state_id)->name ?? '-',
            'city' => City::find($this->city_id)->name ?? '-',
            'issue_date' => $this->issue_date,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status
        ];
    }
}
