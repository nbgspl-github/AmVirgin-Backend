<?php

namespace App\Queries;

use App\Models\Section;
use App\Models\Slider;

class SliderQuery extends AbstractQuery{
	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		$this->active();
		return $this;
	}

	public function shopSection(): self
	{
		$this->query->where('section', Section::Type['Shop']);
		return $this;
	}

	public function entertainmentSection(): self
	{
		$this->query->where('section', Section::Type['Entertainment']);
		return $this;
	}

	protected function model(): string{
		return Slider::class;
	}
}