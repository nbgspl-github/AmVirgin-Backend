<?php

use App\Storage\BaseStorage;

class PublicDisk extends BaseStorage{
	public function access(){
		return \Illuminate\Support\Facades\Storage::disk('public');
	}
}