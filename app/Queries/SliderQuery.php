<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder;

class SliderQuery extends BaseQuery{
	public static function begin(){
		return new self();
	}

	public function displayable(): self{
		$this->active();
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

	protected function model(): string{
		return Slider::class;
	}
}