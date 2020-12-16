<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Videos\JustAddedVideoResource;
use App\Http\Resources\Videos\TopPicksVideoResource;
use App\Http\Resources\Videos\TrendingPicksVideoResource;
use App\Models\Video;
use App\Traits\FluentResponse;
use Throwable;

class TrendController extends ApiController
{
	use FluentResponse;

	public function index ()
	{
		$response = $this->responseApp();
		try {
			$trending = Video::where([
				['trending', true],
				['rank', '>', 0],
			])->orderBy('rank', 'DESC')->get();
			$trending->transform(function (Video $video) {
				return new TrendingPicksVideoResource($video);
			});

			$justAdded = Video::latest()->take(10)->get();
			$justAdded->transform(function (Video $video) {
				return new JustAddedVideoResource($video);
			});

			$topPicks = Video::where([
				['topPick', true],
			])->orderBy('created_at', 'DESC')->get();
			$topPicks->transform(function (Video $video) {
				return new TopPicksVideoResource($video);
			});

			$payload = [
				'trendingPicks' => $trending->all(),
				'justAdded' => $justAdded->all(),
				'topPicks' => $topPicks->all(),
			];
			$response->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $payload)->message('Success');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->setValue('data')->message('Error');
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}