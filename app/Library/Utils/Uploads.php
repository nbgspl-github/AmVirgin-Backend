<?php

namespace App\Library\Utils;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

/**
 * Class Uploads
 * @package App\Library\Utils
 */
class Uploads
{
	public static function access () : Filesystem
	{
		return Storage::disk('secured');
	}

	public static function deleteIfExists (string $path = null)
	{
		if (self::access()->exists($path))
			self::access()->delete($path);
	}

	public static function existsUrl (string $path = null) : ?string
	{
		if (self::access()->exists($path))
			return self::access()->url($path);
		else
			return null;
	}
}