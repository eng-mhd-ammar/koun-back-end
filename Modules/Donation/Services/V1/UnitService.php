<?php

namespace Modules\Donation\Services\V1;

use Modules\Core\DTO\BaseDTO;
use Modules\Donation\Interfaces\V1\Unit\UnitRepositoryInterface;
use Modules\Donation\Interfaces\V1\Unit\UnitServiceInterface;
use Modules\Core\Services\BaseService;

class UnitService extends BaseService implements UnitServiceInterface
{
    public function __construct(protected UnitRepositoryInterface $repository)
    {
    }

    #[Override]
    public function create(BaseDTO $DTO)
    {
        if (!empty($DTO->name)) {
            return $this->repository->create([
                'name' => $DTO->name,
            ]);
        }

        if (!empty($DTO->units)) {
            return $this->repository->createMany($DTO->units);
        }
    }
}
