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
		$current = Carbon::now();
		$oneYearLater = $current->addYear()->toDateTimeString();
		$current = $current->toDateTimeString();
		$this->query->where('validFrom', '<=', $current)->where('validUntil', $oneYearLater);
		return $this;
	}
}