<?php

namespace App\Listeners\Admin\Videos;

use App\Events\Admin\Videos\VideoUpdated;
use App\Models\Video\Video;

class UpdatePendingStatus {
	public function __construct() {
	}

	public function handle(VideoUpdated $event) {
		$series = Video::find($event->eventData());
		if (!is_null($series)) {
			$sources = $series->sources()->get();
			$count = $sources->unique('season')->count();
			$series->setSeasons($count);
			$series->save();
		}
	}
}