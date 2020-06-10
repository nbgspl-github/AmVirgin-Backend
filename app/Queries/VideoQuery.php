<?php

namespace App\Queries;

use App\Classes\Time;
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

	public function movie(): self{
		$this->query->where('type', Video::Type['Movie']);
		return $this;
	}

	public function series(): self{
		$this->query->where('type', Video::Type['Series']);
		return $this;
	}

	public function isNew(): self{
		$lastWeek = Time::mysqlStamp(time() - 604800);
		$this->query->where('created_at', '>=', $lastWeek);
		return $this;
	}
}