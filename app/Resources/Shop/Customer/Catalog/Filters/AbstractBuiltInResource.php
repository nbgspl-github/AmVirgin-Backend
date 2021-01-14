<?php

namespace App\Resources\Shop\Customer\Catalog\Filters;

use App\Library\Utils\Extensions\Arrays;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

abstract class AbstractBuiltInResource
{
	protected ?array $values;
	const COLUMN = 'column';
	protected const KEY = 'filter_key';
	protected const TYPE = 'filter_type';
	protected const MODE = 'filter_mode';
	protected const LABEL = 'filter_label';

	public function __construct ()
	{
		$this->values = Arrays::Empty;
	}

	public abstract function withValues (Collection $values) : self;

	public function toArray ($request) : array
	{
		return [
			'key' => static::KEY,
			'label' => static::LABEL,
			'type' => static::TYPE,
			'mode' => static::MODE,
			'options' => $this->values,
		];
	}
}