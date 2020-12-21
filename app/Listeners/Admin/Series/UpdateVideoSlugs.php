<?php

namespace App\Listeners\Admin\Series;

use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\Video;
use App\Models\VideoSource;

class UpdateVideoSlugs{
	public function __construct(){
	}

	public function handle(TvSeriesUpdated $event){
		$series = Video::find($event->eventData());
		if (!is_null($series)) {
			$languages = $series->sources()->get()->unique('mediaLanguageId');
			$languages->transform(function (VideoSource $source) {
				return MediaLanguage::find($source->mediaLanguageId);
			});
			$qualities = $series->sources()->get()->unique('mediaQualityId');
			$qualities->transform(function (VideoSource $source) {
				return MediaQuality::find($source->mediaQualityId);
			});
			$series->setLanguageSlug($languages);
			$series->setQualitySlug($qualities);
			$series->save();
		}
	}
}