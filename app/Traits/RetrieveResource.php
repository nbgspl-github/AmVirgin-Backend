<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\ModelNotFoundException;

trait RetrieveResource{
	protected static $throwsMessage = '%s not found for given key.';

	/**
	 * Gets the data pointed to by this Id, or null if not found.
	 * @param null $id
	 * @return self
	 */
	public static function retrieve($id = null){
		return self::find($id);
	}

	/**
	 * Gets the data pointed to by this Id, or throws ModelNotFound exception if not found.
	 * @param null $id
	 * @return self
	 * @throws \Throwable
	 */
	public static function retrieveThrows($id = null){
		$model = self::retrieve($id);
		$modelName = __modelNameFromSlug(self::class);
		$msg = sprintf(self::$throwsMessage, $modelName);
		throw_if(null($model), new ModelNotFoundException($msg));
		return $model;
	}

}