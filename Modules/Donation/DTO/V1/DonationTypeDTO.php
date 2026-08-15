<?php

namespace Modules\Donation\DTO\V1;

use Modules\Donation\Requests\V1\DonationType\CreateDonationTypeRequest;
use Modules\Donation\Requests\V1\DonationType\UpdateDonationTypeRequest;
use Modules\Core\DTO\BaseDTO;

class DonationTypeDTO extends BaseDTO
{
    public function __construct(
        public ?string $name,
        public ?string $parent_id,
        public ?array $types,
    ) {
    }

    public static function fromRequest(CreateDonationTypeRequest|UpdateDonationTypeRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            parent_id: $request->validated('parent_id'),
            types: $request->validated('types'),
        );
    }
}
