<?php

namespace Modules\Donation\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Auth\Resources\V1\UserResource;
use Modules\Institution\Resources\V1\BranchResource;

class DonationResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status->label(),
            'sent_at' => $this->formatted_sent_at,
            'notes' => $this->notes,

            'user_user' => new UserResource($this->whenLoaded('userUser')),
            'user_branch' => new BranchResource($this->whenLoaded('userBranch')),
            'donation_items' => DonationResource::collection($this->whenLoaded('donationItems')),
        ];
    }
}
