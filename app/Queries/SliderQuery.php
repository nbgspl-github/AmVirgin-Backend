<?php

namespace App\Queries;

use App\Models\Slider;

class SliderQuery extends AbstractQuery
{
	public static function begin () : self
	{
		return new self();
	}

	public function displayable () : self
	{
		$this->active();
		return $this;
	}

	public function shopSection () : self
	{
		$this->query->where('section', 'shop');
		return $this;
	}

	public function entertainmentSection () : self
	{
		$this->query->where('section', 'entertainment');
		return $this;
	}

	protected function model () : string
	{
		return Slider::class;
	}
}