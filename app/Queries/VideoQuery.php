<?php

namespace App\Queries;

use App\Models\Video;

class VideoQuery extends AbstractQuery{

	protected function model(): string{
		return Video::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		$this->query->where('pending', false);
		return $this;
	}

	public function trending(bool $yes = true): self{
		$this->query->where('trending', $yes);
		return $this;
	}

	public function genre(int $genreId): self{
		$this->query->where('genreId', $genreId);
		return $this;
	}

	public function section(int $sectionId): self{
		$this->query->where('sectionId', $sectionId);
		return $this;
	}
}