<?php

namespace Modules\Address\DTO\V1;

use Modules\Address\Requests\V1\State\CreateStateRequest;
use Modules\Address\Requests\V1\State\UpdateStateRequest;
use Modules\Core\DTO\BaseDTO;

class StateDTO extends BaseDTO
{
    public function __construct(
        public ?string $name,
        public ?array $names,

    ) {
    }

    public static function fromRequest(CreateStateRequest|UpdateStateRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            names: $request->validated('names'),
        );
    }
}
