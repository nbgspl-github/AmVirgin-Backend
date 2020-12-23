<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Http\Resources\Videos\VideoResource;
use App\Library\Enums\Videos\Types;
use App\Library\Utils\Extensions\Str;
use App\Library\Utils\Uploads;
use App\Models\Video\Source;
use App\Models\Video\Video;
use App\Models\Video\WatchLater;
use App\Resources\Shop\Customer\HomePage\TrendingNowVideoResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Throwable;

class VideosController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function show ($id) : JsonResponse
	{
		$response = responseApp();
		try {
			$video = Video::findOrFail($id);
			$payload = new VideoResource($video);
			$payload = $payload->jsonSerialize();
			$payload['content'] = $payload['recommended'] = [];
			if ($video->getType() == Types::Series) {
				$seasons = $video->sources()->get()->groupBy('season')->transform(function (Collection $season) {
					return $season->groupBy('episode')->transform(function (Collection $episode) {
						return [
							'title' => $episode->first()->getTitle(),
							'description' => $episode->first()->getDescription(),
							'options' => $episode->transform(function (Source $source) {
								return [
									'language' => $source->language()->first()->getName(),
									'quality' => $source->mediaQuality()->first()->getName(),
									'url' => Uploads::access()->url($source->getFile()),
									'subtitle' => [
										'available' => Uploads::access()->exists($source->getSubtitle()),
										'url' => Uploads::access()->exists($source->getSubtitle()) ? Uploads::access()->url($source->getSubtitle()) : Str::Empty,
									],
								];
							})->values(),
						];
					})->values();
				})->values();
				$season = 1;
				$seasons = collect($seasons->toArray())->transform(function ($item) use (&$season) {
					return [
						'season' => $season++,
						'episodes' => count($item),
						'content' => $item,
					];
				})->values();
				$payload['content'] = $seasons;
			} elseif ($video->getType() == 'movie') {
				$sources = $video->sources->transform(function (Source $source) {
					return [
						'title' => $source->title,
						'description' => $source->description,
						'language' => $source->language->name,
						'quality' => $source->mediaQuality->name,
						'url' => Uploads::access()->url($source->file),
						'subtitle' => [
							'available' => Uploads::access()->exists($source->subtitle),
							'url' => Uploads::access()->exists($source->subtitle) ? Uploads::access()->url($source->subtitle) : Str::Empty,
						],
					];
				})->values();
				$payload['content'] = $sources;
				$trendingNow = Video::query()->where([['trending', true], ['pending', false], ['type', 'movie']])->orderBy('rating', 'DESC')->limit(15)->get();
				$trendingNow = TrendingNowVideoResource::collection($trendingNow);
				$payload['recommended'] = $trendingNow;
			}

			$response->status(Response::HTTP_OK)->message('Success')->setValue('data', $payload);
		} catch (ModelNotFoundException $exception) {
			$response->status(Response::HTTP_NOT_FOUND)->message('No video/tv-series found for given key.')->setValue('data');
		} catch (Throwable $exception) {
			$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage())->setValue('data');
		} finally {
			return $response->send();
		}
	}

	public function addInWatchLater (Request $request) : JsonResponse
	{
		$response = responseApp();
		$dataSet = [];
		$input = request()->all();
		$rules = [
			'video_id' => "required",
		];
		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {

			$response->status(Response::HTTP_BAD_REQUEST)->message($validator->errors()->first());
			return $response->send();
		} else {

			try {

				$dataSet['customer_id'] = $id = $this->guard()->id();
				$dataSet['video_id'] = $videoId = !empty($request->video_id) ? $request->video_id : '';
				$dataSet['customer_ip'] = !empty($request->customer_ip) ? $request->customer_ip : '';
				$dataSet['customer_user_agent'] = !empty($request->customer_user_agent) ? $request->customer_user_agent : '';
				$dataSet['video_type'] = !empty($request->video_type) ? $request->video_type : '';
				$dataSet['video_count'] = 1;

				$videoData = WatchLater::where(['customer_id' => $id, 'video_id' => $videoId])->first();

				if (!empty($videoData)) {
					$response->status(Response::HTTP_OK)->message('This video is already added in list');
					return $response->send();
				} else {
					$res = WatchLater::create($dataSet);
					$response->status(Response::HTTP_OK)->message('Successfully added in list');
					return $response->send();
				}

			} catch (Throwable $exception) {
				$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
			} finally {
				return $response->send();
			}

		}

	}

	public function removeWatchLater ($id)
	{
		$response = responseApp();

		try {

			$cId = $this->guard()->id();
			$videoData = WatchLater::where(['customer_id' => $cId, 'video_id' => $id])->first();

			if (!empty($videoData)) {
				$videoData->delete();

				$response->status(Response::HTTP_OK)->message('Successfully removed from list');
				return $response->send();
			} else {

				$response->status(Response::HTTP_NOT_FOUND)->message('OPPS! This video is not added in list');
				return $response->send();
			}

		} catch (Throwable $exception) {
			$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}

	}

	public function getWatchLaterVideo ()
	{
		$response = responseApp();
		try {

			$cId = $this->guard()->id();
			$dataSet = WatchLater::with('video')
				->where(['customer_id' => $cId])
				->get();

			$response->status(Response::HTTP_OK)->message('Success')->setValue('data', $dataSet);

		} catch (Throwable $exception) {
			$response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}

	}
}