<?php

namespace Modules\Donation\Services\V1;

use Modules\Core\DTO\BaseDTO;
use Modules\Donation\Interfaces\V1\DonationType\DonationTypeRepositoryInterface;
use Modules\Donation\Interfaces\V1\DonationType\DonationTypeServiceInterface;
use Modules\Core\Services\BaseService;

class DonationTypeService extends BaseService implements DonationTypeServiceInterface
{
    public function __construct(protected DonationTypeRepositoryInterface $repository)
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

        if (!empty($DTO->types)) {
            return $this->repository->createMany($DTO->types);
        }
    }
}
