<?php

namespace App\Classes\Sorting;

class Natural implements SortingAlgorithm{
	public static function obtain(){
		return ['id', 'asc'];
	}
}