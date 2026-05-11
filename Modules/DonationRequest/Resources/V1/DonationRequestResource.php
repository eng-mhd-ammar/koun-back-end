<?php

namespace Modules\DonationRequest\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Resources\V1\UserResource;
use Modules\Institution\Resources\V1\BranchResource;

class DonationRequestResource extends JsonResource
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
            'status' => $this->status,
            'notes' => $this->notes,

            'receiver_user' => new UserResource($this->whenLoaded('receiverUser')),
            'receiver_branch' => new BranchResource($this->whenLoaded('receiverBranch')),
        ];
    }
}
