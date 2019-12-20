<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Base\ResourceController;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TrendController extends ResourceController{

	public function index(){
		$payload = [];
		$trendingPicks = Video::where('trending', true)->get();
	}

	protected function parentProvider(){

	}

	protected function provider(){

	}

	protected function resourceConverter(Model $model){

	}

	protected function collectionConverter(Collection $collection){

	}

	protected function guard(){

	}
}