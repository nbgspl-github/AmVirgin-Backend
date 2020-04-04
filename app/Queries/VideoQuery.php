<?php

namespace App\Queries;

use App\Models\Video;

class VideoQuery extends AbstractQuery{

	protected function model(): string{
		return Video::class;
	}

	public static function begin(): AbstractQuery{
		return new self();
	}

	public function displayable(): self{
		$this->query->where('pending', false);
		return $this;
	}
}