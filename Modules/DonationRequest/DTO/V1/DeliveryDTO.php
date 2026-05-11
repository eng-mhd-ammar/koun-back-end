<?php

namespace Modules\DonationRequest\DTO\V1;

use Modules\DonationRequest\Requests\V1\Delivery\CreateDeliveryRequest;
use Modules\DonationRequest\Requests\V1\Delivery\UpdateDeliveryRequest;
use Modules\Core\DTO\BaseDTO;

class DeliveryDTO extends BaseDTO
{
    public function __construct(
        public ?string $donation_request_id,
        public ?string $delivery_id,
        public ?string $receiver_id,
        public ?int $status,
        public ?string $picked_at,
        public ?string $delivered_at,
    ) {
    }

    public static function fromRequest(CreateDeliveryRequest|UpdateDeliveryRequest $request): self
    {
        return new self(
            donation_request_id: $request->validated('donation_request_id'),
            delivery_id: $request->validated('delivery_id'),
            receiver_id: $request->validated('receiver_id'),
            status: $request->validated('status'),
            picked_at: $request->validated('picked_at'),
            delivered_at: $request->validated('delivered_at'),
        );
    }
}
