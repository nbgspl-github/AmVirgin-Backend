<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\PageSection;
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

	public function shopSection(): self{
		$this->query->where('section', PageSection::Type['Shop']);
		return $this;
	}

	public function entertainmentSection(): self{
		$this->query->where('section', PageSection::Type['Entertainment']);
		return $this;
	}

	protected function model(): string{
		return Slider::class;
	}
}