<?php

namespace Modules\Institution\Interfaces\V1\Branch;

use Illuminate\Database\Eloquent\Model;
use App\Custom\CustomPaginator;
use Illuminate\Database\Eloquent\Collection;

interface BranchRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): CustomPaginator;
    public function show(string $modelId): Model;
    public function create(array $data): Model;
    public function update(string $modelId, array $data): Model;
    public function delete(string $modelId): void;
    public function addScopes(string|array $scopes);
    public function allowedSorts(): array;
    public function allowedFilters(): array;
    public function allowedIncludes(): array;
    public function allowedFields(): array;
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
