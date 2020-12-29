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
}