<?php

namespace Modules\Donation\Interfaces\V1\Unit;

use Modules\Donation\DTO\V1\UnitDTO;

interface UnitServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(UnitDTO $DTO);
    public function update(string $modelId, UnitDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
