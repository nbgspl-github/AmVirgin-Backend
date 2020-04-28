<?php

namespace App\Queries;

use App\Classes\Time;
use App\Models\Announcement;
use Illuminate\Support\Carbon;

class AnnouncementQuery extends AbstractQuery{
	protected function model(): string{
		return Announcement::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		$current = Carbon::now()->toDateTimeString();
		$this->query->where('validFrom', '<=', $current)->where('validUntil', '>=', $current);
		return $this;
	}

	public function excludeDeleted(): self{
		$this->query->whereJsonDoesntContain('deletedBy', auth('seller-api')->id());
		return $this;
	}

	public function excludeRead(): self{
		$this->query->whereJsonDoesntContain('readBy', auth('seller-api')->id());
		return $this;
	}
}