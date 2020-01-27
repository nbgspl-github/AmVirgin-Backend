<?php

namespace App\Queries;

use Illuminate\Support\Collection;

abstract class BaseQuery{
	public function all(): Collection{
		return $this->model()::all();
	}

	protected abstract function model(): string;
}