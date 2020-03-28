<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\Product;
use App\Models\ShopSlider;
use App\Models\Slider;
use Illuminate\Database\Eloquent\Builder;

class ShopSliderQuery extends AbstractQuery{
	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		$this->active();
		return $this;
	}

	protected function model(): string{
		return ShopSlider::class;
	}
}