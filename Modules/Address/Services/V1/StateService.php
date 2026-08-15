<?php

namespace Modules\Address\Services\V1;

use Modules\Address\Interfaces\V1\State\StateRepositoryInterface;
use Modules\Address\Interfaces\V1\State\StateServiceInterface;
use Modules\Address\Models\State;
use Modules\Core\DTO\BaseDTO;
use Modules\Core\Services\BaseService;
use Override;

class StateService extends BaseService implements StateServiceInterface
{
    public function __construct(protected StateRepositoryInterface $repository)
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
    
        if (!empty($DTO->names)) {
                $data = array_map(fn($name) => ['name' => $name], $DTO->names);
            return $this->repository->createMany($data);
        }
    
    }
}
