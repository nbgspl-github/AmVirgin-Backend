<?php

namespace App\Classes\Eloquent;

use App\Classes\Str;

class ModelExtended extends \Illuminate\Database\Eloquent\Model{
	public function getTable(){
		return $this->table ?? Str::slug(Str::pluralStudly(class_basename($this)));
	}
}