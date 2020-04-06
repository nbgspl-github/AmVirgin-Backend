<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractQuery{
	protected ?Builder $query = null;

	protected function __construct(){
		$this->initialize();
	}

	protected function initialize(){
		$this->query = $this->model()::query();
	}

	protected abstract function model(): string;

	public static abstract function begin(): self;

	public abstract function displayable(): self;

	protected function active(): self{
		$this->query->where('active', true);
		return $this;
	}

	public function take(int $limit): self{
		$this->query->take($limit);
		return $this;
	}

	public function orderByAscending(string $column): self{
		$this->query->orderBy($column, 'asc');
		return $this;
	}

	public function orderByDescending(string $column): self{
		$this->query->orderBy($column, 'desc');
		return $this;
	}

	public function orderBy(string $column, string $direction = 'asc'): self{
		$this->query->orderBy($column, $direction);
		return $this;
	}

	public function count(string $column = 'id'): int{
		return $this->query->count($column);
	}

	public function paginate(int $page): \Illuminate\Contracts\Pagination\LengthAwarePaginator{
		return $this->query->paginate($page);
	}

	public function min(string $column): int{
		return $this->query->min($column);
	}

	public function max(string $column): int{
		return $this->query->max($column);
	}

	public function key(int $id, string $primaryKey = 'id'){
		$this->query->where($primaryKey, $id);
		return $this;
	}

	public function first(){
		return $this->query->first();
	}

	public function firstOrFail(){
		return $this->query->firstOrFail();
	}

	public function get($columns = ['*']){
		return $this->query->get($columns);
	}

	public function custom(AbstractQuery $query): self{
		return $this;
	}

	public function latest($column = 'created_at'): self{
		$this->query->latest($column);
		return $this;
	}

	public function withRelations(string ...$relation): self{
		$this->query->with($relation);
		return $this;
	}

	public function search(string $keywords, string $column = 'name'): self{
		$this->query->where($column, 'LIKE', "%{$keywords}%");
		return $this;
	}
}