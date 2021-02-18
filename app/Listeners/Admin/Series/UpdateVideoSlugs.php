<?php

namespace App\Listeners\Admin\Series;

use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Models\Video\Language;
use App\Models\Video\MediaQuality;
use App\Models\Video\Source;
use App\Models\Video\Video;

class UpdateVideoSlugs
{
	public function __construct ()
	{
	}

	public function handle (TvSeriesUpdated $event)
	{
		$series = Video::find($event->eventData());
		if (!is_null($series)) {
			$languages = $series->sources()->get()->unique('mediaLanguageId');
			$languages->transform(function (Source $source) {
				return Language::find($source->mediaLanguageId);
			});
			$qualities = $series->sources()->get()->unique('mediaQualityId');
			$qualities->transform(function (Source $source) {
				return MediaQuality::find($source->mediaQualityId);
			});
			$series->setLanguageSlug($languages);
			$series->setQualitySlug($qualities);
			$series->save();
		}
	}
}