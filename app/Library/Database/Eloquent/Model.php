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
class Model extends \Illuminate\Database\Eloquent\Model
{
	use \App\Traits\MediaLinks;

	protected $guarded = ['id'];

	protected $attributes = [];

	public function __construct (array $attributes = [])
	{
		parent::__construct($attributes);
	}
}