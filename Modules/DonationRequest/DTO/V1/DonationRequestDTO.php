<?php

namespace Modules\DonationRequest\DTO\V1;

use Modules\DonationRequest\Requests\V1\DonationRequest\CreateDonationRequestRequest;
use Modules\DonationRequest\Requests\V1\DonationRequest\UpdateDonationRequestRequest;
use Modules\Core\DTO\BaseDTO;

class DonationRequestDTO extends BaseDTO
{
    public function __construct(
        public ?string $receiver_user_id,
        public ?string $receiver_branch_id,
        public ?string $status,
        public ?string $notes,
    ) {
    }

    public static function fromRequest(CreateDonationRequestRequest|UpdateDonationRequestRequest $request): self
    {
        return new self(
            receiver_user_id: $request->validated('receiver_user_id'),
            receiver_branch_id: $request->validated('receiver_branch_id'),
            status: $request->validated('status'),
            notes: $request->validated('notes'),
        );
    }
}
