<?php

namespace App\Storage;

use App\Classes\Str;

class SecuredDisk extends BaseStorage {
	public static function access() {
		return \Illuminate\Support\Facades\Storage::disk('secured');
	}

	public static function deleteIfExists(string $path = null) {
		if (self::access()->exists($path))
			self::access()->delete($path);
	}

	public static function existsUrl(string $path = null) {
		if (self::access()->exists($path))
			return self::access()->url($path);
		else
			return null;
	}
}