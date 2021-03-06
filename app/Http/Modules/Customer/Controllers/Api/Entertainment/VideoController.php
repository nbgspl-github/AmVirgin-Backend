<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Library\Enums\Videos\Types;
use App\Models\Video\Video;
use Illuminate\Http\JsonResponse;

class VideoController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function show (Video $video) : JsonResponse
	{
		$markedForWatchLater = (
			$this->customer() != null && $this->customer()->hasOnWatchLaterList($video)
		);
		$video->increment('hits');
		return responseApp()->prepare(
			$video->type->is(Types::Movie)
				? (new \App\Http\Modules\Customer\Resources\Entertainment\Video\VideoResource($video, $markedForWatchLater))
				: (new \App\Http\Modules\Customer\Resources\Entertainment\Series\SeriesResource($video, $markedForWatchLater))
		);
	}
}