<?php

namespace App\Repositories\Contracts;

use App\Models\Attribute;
use App\Repositories\BaseRepository;

class AttributesRepository extends BaseRepository{
	protected function model(): string{
		return Attribute::class;
	}
}