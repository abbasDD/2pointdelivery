<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HelperWalletResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'type' => $this->type,
            'booking_id' => Booking::find($this->booking_id)->booking_uuid ?? '-',
            'amount' => $this->amount,
            'note' => $this->note,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
            'status' => $this->status,
            'paid_at' => $this->paid_at
        ];
    }
}
