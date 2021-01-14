<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use Illuminate\Support\Collection;

class FilterResource extends AbstractBuiltInResource
{
	const COLUMN = 'discount';
	const KEY = 'filter_custom';
	const TYPE = 'discount';
	const MODE = 'single';
	const LABEL = 'Discount';

	public function toArray ($request) : array
	{
		return [
			'key' => $this->id(),
			'label' => $this->label(),
			'type' => 'custom',
			'mode' => $this->mode(),
			'options' => $this->attribute->values(),
		];
	}

	public function withValues (Collection $values) : AbstractBuiltInResource
	{
		$this->values = $values->toArray();
		return $this;
	}
}