<?php

namespace App\Filters;

trait GenderFilter{
	public function gender(string $gender): self{
		$column = defined(static::GenderColumnKey) ? static::GenderColumnKey : 'gender';
		$this->query->where($column, $gender);
		return $this;
	}
}