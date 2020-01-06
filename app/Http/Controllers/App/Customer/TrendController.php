<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Base\ResourceController;
use App\Http\Resources\Videos\JustAddedVideoResource;
use App\Http\Resources\Videos\TopPicksVideoResource;
use App\Http\Resources\Videos\TrendingPicksVideoResource;
use App\Models\Video;
use App\Traits\FluentResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TrendController extends ResourceController{
	use FluentResponse;

	public function index(){
		$trending = Video::where([
			['trending', true],
			['rank', '>', 0],
		])->orderBy('rank', 'DESC')->get();
		$trending->transform(function (Video $video){
			return new TrendingPicksVideoResource($video);
		});

		$justAdded = Video::latest()->take(10)->get();
		$justAdded->transform(function (Video $video){
			return new JustAddedVideoResource($video);
		});

		$topPicks = Video::where([
			['topPick', true],
		])->orderBy('created_at', 'DESC')->get();
		$topPicks->transform(function (Video $video){
			return new TopPicksVideoResource($video);
		});

		$payload = [
			'trendingPicks' => $trending->all(),
			'justAdded' => $justAdded->all(),
			'topPicks' => $topPicks->all(),
		];

		return $this->success()->status(HttpOkay)->setValue('data', $payload)->send();
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