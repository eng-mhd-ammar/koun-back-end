<?php

namespace Modules\Institution\Interfaces\V1\Institution;

use Modules\Institution\DTO\V1\InstitutionDTO;

interface InstitutionServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(InstitutionDTO $DTO);
    public function update(string $modelId, InstitutionDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
