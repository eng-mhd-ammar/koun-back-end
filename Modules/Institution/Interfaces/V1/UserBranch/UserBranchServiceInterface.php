<?php

namespace Modules\Institution\Interfaces\V1\UserBranch;

use Modules\Institution\DTO\V1\UserBranchDTO;

interface UserBranchServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(UserBranchDTO $DTO);
    public function update(string $modelId, UserBranchDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
