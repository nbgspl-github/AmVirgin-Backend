<?php

namespace App\Http\Modules\Admin\Observers\Videos;

class VideoObserver
{
	public function updating (\App\Models\Video\Video $video)
	{
		if ($video->isDirty('rank')) {
			\App\Models\Video\Video::query()->where('rank', $video->rank)->update(['rank' => 0]);
		}
	}
}