<?php

namespace App\Queries;

use App\Models\ShopSlider;

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