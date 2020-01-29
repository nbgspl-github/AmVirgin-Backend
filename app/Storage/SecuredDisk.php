<?php

namespace App\Storage;

class SecuredDisk extends BaseStorage{
	public static function access(){
		return \Illuminate\Support\Facades\Storage::disk('secured');
	}
}