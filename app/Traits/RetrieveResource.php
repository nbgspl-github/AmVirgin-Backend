<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait RetrieveResource
{
	protected static string $throwsMessage = 'Could not find %s for given key.';

	/**
	 * Gets the data pointed to by this Id, or null if not found.
	 * @param null $id
	 * @return static
	 */
	public static function retrieve ($id = null) : self
	{
		return self::find($id);
	}

	/**
	 * Gets the data pointed to by this Id, or throws ModelNotFound exception if not found.
	 * @param null $id
	 * @return static
	 * @throws ModelNotFoundException
	 * @throws \Throwable
	 */
	public static function retrieveThrows ($id = null) : self
	{
		$model = self::retrieve($id);
		$modelName = __modelNameFromSlug(self::class);
		$msg = sprintf(self::$throwsMessage, lcfirst($modelName));
		throw_if(is_null($model), new ModelNotFoundException($msg));
		return $model;
	}

}