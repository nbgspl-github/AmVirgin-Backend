<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder;

class SliderQuery implements QueryProvider{
	protected $model = Slider::class;
	private ?Builder $query = null;

	public static function new(){
		return new self();
	}

	private function initializeIfNull(){
		if ($this->query == null)
			$this->query = $this->model::query();
	}

	public function displayable(): self{
		$this->initializeIfNull();
		$this->query->where('active', true);
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