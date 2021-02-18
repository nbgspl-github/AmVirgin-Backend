<?php

namespace App\Http\Modules\Admin\Observers\Videos;

class SnapObserver
{
	public function deleting (\App\Models\Video\Snap $snap)
	{
		\App\Library\Utils\Uploads::deleteIfExists($snap->getRawOriginal('file'));
	}
}