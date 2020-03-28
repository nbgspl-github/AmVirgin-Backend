<?php

namespace App\Queries;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseQuery{
	protected ?Builder $query = null;

	protected function __construct(){
		$this->initialize();
	}

	protected function initialize(){
		$this->query = $this->model()::query();
	}

	protected abstract function model(): string;

	protected function active(): self{
		$this->query->where('active', true);
		return $this;
	}

	public function take(int $limit): self{
		$this->query->take($limit);
		return $this;
	}
}