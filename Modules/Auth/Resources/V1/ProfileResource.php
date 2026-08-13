<?php

namespace Modules\Auth\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Donation\Resources\V1\DonationRequestResource;
use Modules\Donation\Resources\V1\DonationResource;
use Modules\DonationRequest\Resources\V1\DonationRequestResource as V1DonationRequestResource;
use Modules\Institution\Resources\V1\BranchResource;
use Modules\Institution\Resources\V1\InstitutionResource;
use Modules\Institution\Resources\V1\UserBranchResource;

class ProfileResource extends JsonResource
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
            'avatar' => $this->avatar,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
            'birthday' => $this->formatted_birthday,
            'gender' => $this->gender,

            'roles' => $this->getRoleNames(),
            'owned_institutions' => InstitutionResource::collection($this->whenLoaded('ownedInstitutions')),
            'member_institutions' => InstitutionResource::collection($this->whenLoaded('memberInstitutions')),
            'user_branches' => UserBranchResource::collection($this->whenLoaded('userBranches')),
            'branches' => BranchResource::collection($this->whenLoaded('branches')),

            'donations' => DonationResource::collection($this->whenLoaded('donations')),
            'donations_requests' => V1DonationRequestResource::collection($this->whenLoaded('donationsRequests')),
        ];
    }
}
