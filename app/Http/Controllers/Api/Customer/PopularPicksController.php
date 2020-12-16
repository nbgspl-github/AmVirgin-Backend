<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Videos\PopularVideosResource;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PopularPicksController extends ApiController
{
	public function index ()
	{
		$response = $this->responseApp();
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
			$response->status(HttpOkay)->setValue('data', $payload)->message('Success');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->setValue('data')->message('Error');
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return Auth::guard('customer-api');
	}
}