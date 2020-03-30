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

	public function first(){
		return $this->query->first();
	}

	public function firstOrFail(){
		return $this->query->firstOrFail();
	}

	public function get(){
		return $this->query->get();
	}
}