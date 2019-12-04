<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Provides functionality to generate urls for downloadable resources.
 * @package App\Traits
 */
trait GenerateUrls {
	public function __call($name, $arguments) {
		$prefix = 'url';
		if (Str::startsWith($name, $prefix)) {
			$exists = method_exists(self::class, 'resourceSchema');
			$resourceSchema = $exists ? self::resourceSchema() : [];
			if (count($resourceSchema) > 0) {
				$resourceName = substr($name, strlen($prefix) - 1);
				$methodName = $prefix . $resourceName;
				if (!method_exists(self::class, $methodName) && isset($resourceSchema[$resourceName])) {
					try {
						return Storage::url($resourceSchema[$resourceName]);
					} catch (\Exception $e) {
						return null;
					}
				}
			}
			else {
				return parent::__call($name, $arguments);
			}
		}
		return parent::__call($name, $arguments);
	}
}