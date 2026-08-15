<?php

namespace Modules\Institution\Services\V1;

use Modules\Institution\Interfaces\V1\Branch\BranchRepositoryInterface;
use Modules\Institution\Interfaces\V1\Branch\BranchServiceInterface;
use Modules\Core\Services\BaseService;
use Override;

class BranchService extends BaseService implements BranchServiceInterface
{
    public function __construct(protected BranchRepositoryInterface $repository)
    {
    }

    #[Override]
    public function create($DTO)
    {
        $model = parent::create($DTO);

        if ($DTO->users) {
            $model->members()->sync($DTO->users);
        }

        $model->address()->create($DTO->address);

        return $model;
    }

    #[Override]
    public function update(string $modelId, $DTO)
    {
        $model = parent::update($modelId, $DTO);

        if ($DTO->users) {
            $model->members()->sync($DTO->users);
        }

        if($DTO->address) {
            if ($DTO->address['id']) {
                $model->address()->update($DTO->address);
            } else {
                $model->address()->create($DTO->address);
            }
        }

        return $model;
    }
}
