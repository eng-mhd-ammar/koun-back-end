<?php

namespace Modules\Donation\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\DonationRequest\Resources\V1\DonationRequestItemResource;

class DonationItemResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'notes' => $this->notes,

            'unit' => new UnitResource($this->whenLoaded('unit')),
            'donation-type' => new DonationTypeResource($this->whenLoaded('donationType')),
            'donation' => new DonationResource($this->whenLoaded('donation')),
            'donation_request_items' => DonationRequestItemResource::collection($this->whenLoaded('donationRequestItems')),
        ];
    }
}
