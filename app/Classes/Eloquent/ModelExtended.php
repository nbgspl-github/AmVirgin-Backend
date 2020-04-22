<?php

namespace App\Classes\Eloquent;

use App\Classes\Str;

class ModelExtended extends \Illuminate\Database\Eloquent\Model{
	public function getTable(){
		return $this->table ?? Str::camel(Str::pluralStudly(class_basename($this)));
	}
}