<?php

namespace App\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Schema;

class ExtendedBlueprint extends Blueprint{
	public function file(string $column, $length = 4096): ColumnDefinition{
		return $this->string($column, $length);
	}

	public function dropColumn($columns){
		if (Schema::hasColumn($this->table, $columns))
			parent::dropColumn($columns);
	}

	public function activeStatus(string $column = 'active'){
		return $this->boolean('active')->default(false);
	}
}