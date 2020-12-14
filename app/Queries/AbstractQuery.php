<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;

abstract class AbstractQuery
{
	protected ?Builder $query = null;
	protected static string $throwsMessage = 'Could not find %s for given key.';

	protected function __construct ()
	{
		$this->initialize();
	}

	protected function initialize ()
	{
		$this->query = $this->model()::query();
	}

	protected abstract function model (): string;

	public static abstract function begin (): self;

	public abstract function displayable (): self;

	public function active ($state = true): self
	{
		$this->query->where('active', $state);
		return $this;
	}

	public function take (int $limit): self
	{
		$this->query->take($limit);
		return $this;
	}

	public function limit (int $limit): self
	{
		$this->query->limit($limit);
		return $this;
	}

	public function orderByAscending (string $column): self
	{
		$this->query->orderBy($column, 'asc');
		return $this;
	}

	public function orderByDescending (string $column): self
	{
		$this->query->orderBy($column, 'desc');
		return $this;
	}

	public function orderBy (string $column, string $direction = 'asc'): self
	{
		$this->query->orderBy($column, $direction);
		return $this;
	}

	public function count (string $column = 'id'): int
	{
		return $this->query->count($column);
	}

	public function paginate (int $page = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		return $this->query->paginate($page);
	}

	public function min (string $column): int
	{
		return $this->query->min($column);
	}

	public function max (string $column): int
	{
		return $this->query->max($column);
	}

	public function key (int $id, string $primaryKey = 'id')
	{
		$this->query->where($primaryKey, $id);
		return $this;
	}

	public function first ()
	{
		return $this->query->first();
	}

	public function firstOrFail ()
	{
		try {
			return $this->query->firstOrFail();
		} catch (ModelNotFoundException $exception) {
			$modelName = __modelNameFromSlug($this->model());
			if (property_exists($this, 'modelUserString')) {
				$modelName = $this->modelUserString;
			}
			$msg = sprintf(self::$throwsMessage, lcfirst($modelName));
			throw new ModelNotFoundException($msg);
		}
	}

	public function get ($columns = ['*'])
	{
		return $this->query->get($columns);
	}

	public function custom (AbstractQuery $query): self
	{
		return $this;
	}

	public function latest ($column = 'created_at'): self
	{
		$this->query->latest($column);
		return $this;
	}

	public function withRelations (string ...$relation): self
	{
		$this->query->with($relation);
		return $this;
	}

	public function search (string $keywords, string $column = 'name'): self
	{
		$this->query->where($column, 'LIKE', "%{$keywords}%");
		return $this;
	}

	public function orSearch (string $keywords, string $column = 'name'): self
	{
		$this->query->orWhere($column, 'LIKE', "%{$keywords}%");
		return $this;
	}

	public function withWhere (string $column = '', string $keywords = ''): self
	{
		$this->query->where($column, $keywords);
		return $this;
	}

	public function withWhereBetween (string $column = '', $fromDate = '', $toDate = ''): self
	{
		$this->query->whereBetween($column, [$fromDate . " 00:00:00", $toDate . " 23:59:59"]);
		return $this;
	}

	public function withinCurrentMonth ($inclusive = true, $timestamp = 'created_at'): self
	{
		if ($inclusive) {
			$monthBegin = Carbon::now()->firstOfMonth()->format('Y-m-d H:i:s');
			$monthEnd = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
		} else {
			$monthBegin = Carbon::now()->firstOfMonth()->addDay()->format('Y-m-d H:i:s');
			$monthEnd = Carbon::now()->endOfMonth()->subDay()->format('Y-m-d H:i:s');
		}
		$this->query->whereBetween($timestamp, [$monthBegin, $monthEnd]);
		return $this;
	}

	public function withinPreviousMonth ($inclusive = true, $timestamp = 'created_at'): self
	{
		if ($inclusive) {
			$monthBegin = Carbon::now()->subMonth()->firstOfMonth()->format('Y-m-d H:i:s');
			$monthEnd = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d H:i:s');
		} else {
			$monthBegin = Carbon::now()->subMonth()->firstOfMonth()->addDay()->format('Y-m-d H:i:s');
			$monthEnd = Carbon::now()->subMonth()->endOfMonth()->subDay()->format('Y-m-d H:i:s');
		}
		$this->query->whereBetween($timestamp, [$monthBegin, $monthEnd]);
		return $this;
	}

	public function whereIn (string $column, array $items): self
	{
		$this->query->whereIn($column, $items);
		return $this;
	}
}