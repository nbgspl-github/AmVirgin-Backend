<?php

namespace App\Library\Database\Eloquent;

use Illuminate\Support\Carbon;

/**
 * Extends the base class provided by Laravel to
 * provide some boilerplate code.
 * @package App\Library\Database\Eloquent
 * @property int|string $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
abstract class Model extends \Illuminate\Database\Eloquent\Model
{
	use \App\Traits\MediaLinks;

	use \Illuminate\Database\Eloquent\Factories\HasFactory;

	protected $guarded = ['id'];

	public function __construct (array $attributes = [])
	{
		parent::__construct($attributes);
	}

	/**
	 * Gets the name of table bound to this model.
	 * @return string
	 */
	public static function tableName () : string
	{
		return with(new static)->getTable();
	}

	public function increment ($column, $amount = 1, array $extra = [])
	{
		return parent::increment($column, $amount, $extra);
	}

	public function decrement ($column, $amount = 1, array $extra = [])
	{
		return parent::decrement($column, $amount, $extra);
	}

	/**
	 * Returns an instance of database existence validation constraint Rule.
	 * @param string $column
	 * @return \Illuminate\Validation\Rules\Exists
	 */
	public static function exists ($column = 'id') : \Illuminate\Validation\Rules\Exists
	{
		return \App\Library\Utils\Extensions\Rule::exists(static::tableName(), $column);
	}
}