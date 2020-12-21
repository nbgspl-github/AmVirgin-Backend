<?php

namespace App\Library\Extensions;

use Illuminate\Support\Carbon;

/**
 * Extends the base class provided by Laravel to
 * provide some boilerplate code.
 * @package App\Library\Extensions
 * @property int|string $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ModelExtended extends \Illuminate\Database\Eloquent\Model
{
	protected $guarded = ['id'];
}