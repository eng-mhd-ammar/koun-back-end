<?php

namespace Modules\Institution\Interfaces\V1\Branch;

use Modules\Institution\DTO\V1\BranchDTO;

interface BranchServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(BranchDTO $DTO);
    public function update(string $modelId, BranchDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
