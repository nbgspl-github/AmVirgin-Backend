<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder;

class SliderQuery extends AbstractQuery{
	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		$this->active();
		return $this;
	}

	protected function model(): string{
		return Slider::class;
	}
}