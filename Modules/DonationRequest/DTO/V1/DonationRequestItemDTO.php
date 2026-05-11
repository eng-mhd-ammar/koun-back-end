<?php

namespace Modules\DonationRequest\DTO\V1;

use Modules\DonationRequest\Requests\V1\DonationRequestItem\CreateDonationRequestItemRequest;
use Modules\DonationRequest\Requests\V1\DonationRequestItem\UpdateDonationRequestItemRequest;
use Modules\Core\DTO\BaseDTO;

class DonationRequestItemDTO extends BaseDTO
{
    public function __construct(
        public ?string $donation_request_id,
        public ?string $donation_item_id,
        public ?int $requested_quantity,
        public ?int $approved_quantity,
        public ?int $received_quantity,
    ) {
    }

    public static function fromRequest(CreateDonationRequestItemRequest|UpdateDonationRequestItemRequest $request): self
    {
        return new self(
            donation_request_id: $request->validated('donation_request_id'),
            donation_item_id: $request->validated('donation_item_id'),
            requested_quantity: $request->validated('requested_quantity'),
            approved_quantity: $request->validated('approved_quantity'),
            received_quantity: $request->validated('received_quantity'),
        );
    }
}
