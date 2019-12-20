<?php

namespace App\Queries;

use App\Models\Video;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;

class VideoQuery extends BaseQuery{
	use RetrieveResource;
	use RetrieveCollection;

	public function trending(){

	}

	public function all(){

	}

	protected function model(): string{
		return Video::class;
	}
}