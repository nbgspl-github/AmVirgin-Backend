<?php

namespace App\Storage;

class SecuredDisk extends BaseStorage{
	public static function access(){
		return \Illuminate\Support\Facades\Storage::disk('secured');
	}

	public static function deleteIfExists(string $path = null){
		if (self::access()->exists($path))
			self::access()->delete($path);
	}
}