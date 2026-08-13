<?php

namespace Modules\Auth\Services\V1;

use Modules\Auth\Exceptions\UserException;
use Modules\Auth\Interfaces\V1\User\UserRepositoryInterface;
use Modules\Auth\Interfaces\V1\User\UserServiceInterface;
use Modules\Core\DTO\BaseDTO;
use Modules\Core\Services\BaseService;
use Override;

class UserService extends BaseService implements UserServiceInterface
{
    public function __construct(protected UserRepositoryInterface $repository)
    {
    }

    #[Override]
    public function create(BaseDTO $DTO)
    {
        $model = parent::create($DTO);
        $model->roles()->sync($DTO->roles);
        return $model;
    }

    #[Override]
    public function update($modelId, BaseDTO $DTO)
    {
        $model = parent::update($modelId, $DTO);
        $model->roles()->sync($DTO->roles);
        return $model;
    }

    public function restore($model_id)
    {
        $model = $this->repository->withTrashed()->show($model_id);
        $this->repository = $this->repository->resetQuery();
        $models = $this->repository->where('phone', $model->phone)->all();
        if (!empty($models->toArray())) {
            $this->throwAlreadyPhoneTaken();
        }
        $model->restore();
        return $model;
    }

    public function throwAlreadyPhoneTaken(): void
    {
        UserException::alreadyPhoneTaken();
    }
}
