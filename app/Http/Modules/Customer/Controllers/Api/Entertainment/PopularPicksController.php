<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Http\Resources\Videos\PopularVideosResource;
use App\Models\Video\Video;
use Throwable;

class PopularPicksController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function index ()
	{
		$response = responseApp();
		try {
			$popular = Video::where([
				['trending', true],
				['rank', '>', 0],
			])->orWhere([
				['topPick', true],
			])->orderBy('rank', 'DESC')->orderBy('hits', 'DESC')->get();

			$popular->transform(function (Video $video) {
				return new PopularVideosResource($video);
			});
			$payload = $popular->all();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $payload)->message('Success');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->setValue('data')->message('Error');
		} finally {
			return $response->send();
		}
	}
}