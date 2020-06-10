<?php

namespace App\Classes\Eloquent;

use App\Classes\Arrays;
use App\Classes\Str;
use App\Storage\SecuredDisk;
use Illuminate\Support\Collection as BaseCollection;

class ModelExtended extends \Illuminate\Database\Eloquent\Model{
	protected $fillable = ['*'];
	protected $guarded = [
		'id',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
	/**
	 * @var array Attributes whose values should be treated as files.
	 */
	protected $media = [];

	public function getTable(){
		return $this->table ?? $this->resolveTable();
	}

	protected function resolveTable(): ?string{
		$snakeCased = Str::snake(Str::pluralStudly(class_basename($this)));
		return str_replace('_', '-', $snakeCased);
	}

	/**
	 * Cast an attribute to a native PHP type.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return mixed
	 */
	protected function castAttribute($key, $value){
		if (is_null($value)) {
			return $value;
		}

		switch ($this->getCastType($key)) {
			case 'int':
			case 'integer':
				return (int)$value;
			case 'real':
			case 'float':
			case 'double':
				return $this->fromFloat($value);
			case 'decimal':
				return $this->asDecimal($value, explode(':', $this->getCasts()[$key], 2)[1]);
			case 'string':
				return (string)$value;
			case 'bool':
			case 'boolean':
				return (bool)$value;
			case 'object':
				return $this->fromJson($value, true);
			case 'array':
			case 'json':
				return $this->fromJson($value);
			case 'collection':
				return new BaseCollection($this->fromJson($value));
			case 'date':
				return $this->asDate($value);
			case 'datetime':
			case 'custom_datetime':
				return $this->asDateTime($value);
			case 'timestamp':
				return $this->asTimestamp($value);
			case 'uri':
				return makeUrl($value);
			default:
				return $value;
		}
	}
}