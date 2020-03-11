<?php

namespace App\Listeners\Admin\Videos;

use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Events\Admin\Videos\VideoUpdated;
use App\Models\Video;

class UpdatePendingStatus {
	public function __construct() {
	}

	public function handle(VideoUpdated $event) {
		$series = Video::retrieve($event->eventData());
		if (!null($series)) {
			$sources = $series->sources()->get();
			$count = $sources->unique('season')->count();
			$series->setSeasons($count);
			$series->save();
		}
	}
}