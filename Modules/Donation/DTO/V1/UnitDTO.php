<?php

namespace Modules\Donation\DTO\V1;

use Modules\Donation\Requests\V1\Unit\CreateUnitRequest;
use Modules\Donation\Requests\V1\Unit\UpdateUnitRequest;
use Modules\Core\DTO\BaseDTO;

class UnitDTO extends BaseDTO
{
    public function __construct(
        public ?string $name,
        public ?string $description,
        public ?array $units,
    ) {
    }

    public static function fromRequest(CreateUnitRequest|UpdateUnitRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            description: $request->validated('description'),
            units: $request->validated('units'),
        );
    }
}
