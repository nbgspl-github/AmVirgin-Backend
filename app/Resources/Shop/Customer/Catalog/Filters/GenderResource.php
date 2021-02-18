<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use Illuminate\Support\Collection;

class GenderResource extends AbstractBuiltInResource
{
	public const COLUMN = 'idealFor';

	public function toArray ($request)
	{
		return [
			'label' => $this->label(),
			'builtIn' => $this->builtIn(),
			'type' => $this->builtInType(),
			'mode' => $this->mode(),
			'options' => $this->values,
		];
	}

	public function withValues(Collection $values): self{
		return $this;
	}
}