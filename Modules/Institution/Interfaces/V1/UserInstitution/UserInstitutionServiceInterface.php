<?php

namespace Modules\Institution\Interfaces\V1\UserInstitution;

use Modules\Institution\DTO\V1\UserInstitutionDTO;

interface UserInstitutionServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(UserInstitutionDTO $DTO);
    public function update(string $modelId, UserInstitutionDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
