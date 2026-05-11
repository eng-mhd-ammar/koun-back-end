<?php

namespace Modules\Address\Interfaces\V1\State;

use Modules\Address\DTO\V1\StateDTO;

interface StateServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(StateDTO $DTO);
    public function update(string $modelId, StateDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
