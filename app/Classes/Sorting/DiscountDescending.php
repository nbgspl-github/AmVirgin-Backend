<?php

namespace App\Classes\Sorting;

class DiscountDescending implements SortingAlgorithm{
	public static function obtain(){
		return ['id', 'asc'];
	}
}