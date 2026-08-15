<?php

namespace Modules\Core\Repositories;

use App\Custom\CustomPaginator;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Modules\Auth\Models\User;
use Spatie\QueryBuilder\AllowedFilter;

class BaseRepository
{
    protected $model = Model::class;
    protected $query;
    protected $originalQuery;

    public function __construct($query = null)
    {
        $this->initQuery($query);
    }

    /** ---------------- Core Setup ---------------- */
    protected function initQuery($query = null): void
    {
        // dd($this->allowedSorts());
        $this->query = $query ?? QueryBuilder::for($this->model)
            ->allowedSorts('created_at', ...$this->allowedSorts())
            ->allowedFilters(AllowedFilter::trashed(), ...$this->allowedFilters())
            ->allowedFields(...$this->allowedFields())
            ->allowedIncludes(...$this->allowedIncludes())
            ->defaultSort('-created_at');

        $this->originalQuery = clone $this->query;
    }

    public function resetQuery(): static
    {
        $this->query = clone $this->originalQuery;
        return $this;
    }

    public function setConnection(string $connection): static
    {
        $this->model = $this->model::on($connection);
        $this->initQuery();
        return $this;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;
        $this->initQuery();
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query): static
    {
        $this->query = $query;
        return $this;
    }

    /** ---------------- CRUD ---------------- */
    public function all(): Collection
    {
        return $this->query->get();
    }

    public function paginate(int $perPage = 10): CustomPaginator
    {
        return $this->query->paginate($perPage);
    }

    public function show($modelId, array $with = []): Model
    {
        return $this->query->with($with)->findOrFail($modelId);
    }

    public function create(array $data): Model
    {
        return $this->model::create($data);
    }

    public function createMany(array $data): bool
    {
        return $this->model::insert($data);
    }

    public function update(string $modelId, array $data): Model
    {
        $model = $this->query->findOrFail($modelId);
        $model->fill($data);
        $model->save();
        return $model;
    }

    public function updateMany(array $data): int
    {
        return $this->query->update($data);
    }

    public function delete($modelId): void
    {
        $this->query->findOrFail($modelId)->delete();
    }

    public function deleteMany(array $ids): void
    {
        $this->query->whereIn('id', $ids)->delete();
    }

    public function forceDeleteMany(array $ids): void
    {
        $this->query->withTrashed()->whereIn('id', $ids)->forceDelete();
    }

    public function restoreMany(array $ids): void
    {
        $this->query->onlyTrashed()->whereIn('id', $ids)->restore();
    }

    public function forceDelete($modelId): void
    {
        $this->query->withTrashed()->findOrFail($modelId)->forceDelete();
    }

    public function restore($modelId): Model
    {
        $model = $this->query->onlyTrashed()->findOrFail($modelId);
        $model->restore();
        return $model;
    }

    public function switchActivation($modelId): Model
    {
        $model = $this->query->findOrFail($modelId);
        $model->is_active = !$model->is_active;
        $model->save();
        return $model;
    }

    /** ---------------- Query Helpers ---------------- */
    public function addScopes(array|string $scopes): static
    {
        if (is_string($scopes)) {
            $scopes = [$scopes => []];
        }

        foreach ($scopes as $scope => $arguments) {
            $this->query = $this->query->$scope(...$arguments);
        }

        return $this;
    }

    public function with(array|string $relations): static
    {
        $this->query = $this->query->with($relations);
        return $this;
    }

    public function withExists(array|string $relations): static
    {
        $this->query = $this->query->withExists($relations);
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->query = $this->query->orderBy($column, $direction);
        return $this;
    }

    public function where($column = null, $value = null, $operator = '='): static
    {
        if (is_string($column)) {
            $this->query = $this->query->where($column, $operator, $value);
        } elseif (is_callable($column)) {
            $this->query = $this->query->where($column);
        }
        return $this;
    }

    public function onlyTrashed(): static
    {
        $this->query = $this->query->onlyTrashed();
        return $this;
    }

    public function withTrashed(): static
    {
        $this->query = $this->query->withTrashed();
        return $this;
    }

    public function orWhere(string $column, $value, string $operator = '='): static
    {
        $this->query = $this->query->orWhere($column, $operator, $value);
        return $this;
    }

    public function whereIn(string $column, array $values): static
    {
        $this->query = $this->query->whereIn($column, $values);
        return $this;
    }

    public function whereHas(string $relation, $callback = null): static
    {
        $this->query = $callback && is_callable($callback)
            ? $this->query->whereHas($relation, $callback)
            : $this->query->whereHas($relation);

        return $this;
    }

    public function orWhereHas(string $relation, $callback = null): static
    {
        $this->query = $callback && is_callable($callback)
            ? $this->query->orWhereHas($relation, $callback)
            : $this->query->orWhereHas($relation);

        return $this;
    }

    public function firstWhere(string $column, $value): ?Model
    {
        return $this->query->where($column, $value)->first();
    }

    public function addQuery(callable $callback)
    {
        $result = $callback($this->query);

        if ($result instanceof \Illuminate\Database\Eloquent\Builder
            || $result instanceof \Illuminate\Database\Query\Builder
            || $result instanceof QueryBuilder) {
            $this->query = $result;
            return $this;
        }

        return $result;
    }

    /** ---------------- Configurable ---------------- */
    public function allowedSorts(): array
    {
        return [];
    }
    public function allowedFilters(): array
    {
        return [];
    }
    public function allowedIncludes(): array
    {
        return [];
    }
    public function allowedFields(): array
    {
        return [];
    }

    /** ---------------- Debug ---------------- */
    public function ddRawSql(): void
    {
        $this->query->ddRawSql();
    }
}
