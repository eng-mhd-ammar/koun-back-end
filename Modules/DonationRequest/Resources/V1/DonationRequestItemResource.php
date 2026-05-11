<?php

namespace Modules\DonationRequest\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Resources\V1\UserResource;
use Modules\DonationRequest\Resources\V1\DonationRequestResource;

class DonationRequestItemResource extends JsonResource
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
            // 'status' => $this->status,
            // 'picked_at' => $this->formatted_picked_at,
            // 'delivered_at' => $this->formatted_delivered_at,

            // 'donation_request' => new DonationRequestResource($this->whenLoaded('donationRequest')),
            // 'receiver' => new UserResource($this->whenLoaded('receiver')),
            // 'delivery' => new UserResource($this->whenLoaded('delivery')),
        ];
    }
}
