<?php

namespace App\Queries;

use App\Models\Video;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Support\Collection;

class VideoQuery extends BaseQuery{
	use RetrieveResource;
	use RetrieveCollection;

	public function trending(){

	}

	protected function model(): string{
		return Video::class;
	}
}