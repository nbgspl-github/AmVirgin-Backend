<?php

namespace App\Listeners\Admin\Series;

use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Models\Video;

class UpdateSeasonCount{
	public function __construct(){
	}

	public function handle(TvSeriesUpdated $event){
		$series = Video::retrieve($event->eventData());
		if (!is_null($series)) {
			$sources = $series->sources()->get();
			$count = $sources->unique('season')->count();
			$series->setSeasons($count);
			$series->save();
		}
	}
}
