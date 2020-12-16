<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use App\Library\Utils\Extensions\Arrays;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

abstract class AbstractBuiltInResource extends JsonResource{
	protected ?array $values;
	public const RequiredColumn = null;

	public function __construct($resource){
		parent::__construct($resource);
		$this->values = Arrays::Empty;
	}

	protected function mode(){
		return $this->allowMultiValue() ? 'multiple' : 'single';
	}

	public abstract function withValues(Collection $values): self;
}