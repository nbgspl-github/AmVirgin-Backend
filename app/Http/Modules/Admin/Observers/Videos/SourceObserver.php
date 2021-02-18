<?php

namespace App\Http\Modules\Admin\Observers\Videos;

class SourceObserver
{
	public function created (\App\Models\Video\Source $source)
	{
		$media = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('secured')->open($source->getRawOriginal('file'));
		$seconds = $media->getDurationInSeconds();
		$source->update([
			'duration' => \App\Library\Utils\Extensions\Time::toDuration($seconds, "%02d:%02d:%02d")
		]);
	}
}