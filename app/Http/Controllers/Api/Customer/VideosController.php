<?php

namespace App\Http\Controllers\Api\Customer;

use App\Classes\Str;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Videos\VideoResource;
use App\Interfaces\VideoTypes;
use App\Models\Video;
use App\Models\VideoSource;
use App\Models\WatchLaterVideo;
use App\Resources\Shop\Customer\HomePage\TrendingNowVideoResource;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Throwable;

class VideosController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function show ($id)
	{
		$response = responseApp();
		try {
			$video = Video::retrieveThrows($id);
			$payload = new VideoResource($video);
			$payload = $payload->jsonSerialize();
			$payload['content'] = $payload['recommended'] = [];
			if ($video->getType() == VideoTypes::Series) {
				$seasons = $video->sources()->get()->groupBy('season')->transform(function (Collection $season) {
					return $season->groupBy('episode')->transform(function (Collection $episode) {
						return [
							'title' => $episode->first()->getTitle(),
							'description' => $episode->first()->getDescription(),
							'options' => $episode->transform(function (VideoSource $source) {
								return [
									'language' => $source->language()->first()->getName(),
									'quality' => $source->mediaQuality()->first()->getName(),
									'url' => SecuredDisk::access()->url($source->getFile()),
									'subtitle' => [
										'available' => SecuredDisk::access()->exists($source->getSubtitle()),
										'url' => SecuredDisk::access()->exists($source->getSubtitle()) ? SecuredDisk::access()->url($source->getSubtitle()) : Str::Empty,
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
				$sources = $video->sources->transform(function (VideoSource $source) {
					return [
						'title' => $source->title,
						'description' => $source->description,
						'language' => $source->language->name,
						'quality' => $source->mediaQuality->name,
						'url' => SecuredDisk::access()->url($source->file),
						'subtitle' => [
							'available' => SecuredDisk::access()->exists($source->subtitle),
							'url' => SecuredDisk::access()->exists($source->subtitle) ? SecuredDisk::access()->url($source->subtitle) : Str::Empty,
						],
					];
				})->values();
				$payload['content'] = $sources;
				$trendingNow = Video::query()->where([['trending', true], ['pending', false], ['type', 'movie']])->orderBy('rating', 'DESC')->limit(15)->get();
				$trendingNow = TrendingNowVideoResource::collection($trendingNow);
				$payload['recommended'] = $trendingNow;
			}

			$response->status(HttpOkay)->message('Success')->setValue('data', $payload);
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('No video/tv-series found for given key.')->setValue('data');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage())->setValue('data');
		} finally {
			return $response->send();
		}
	}

	public function addInWatchLater (Request $request)
	{
		$response = responseApp();
		$dataSet = [];
		$input = request()->all();
		$rules = [
			'video_id' => "required",
		];
		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {

			$response->status(HttpInvalidRequestFormat)->message($validator->errors()->first());
			return $response->send();
		} else {

			try {

				$dataSet['customer_id'] = $id = $this->guard()->id();
				$dataSet['video_id'] = $videoId = !empty($request->video_id) ? $request->video_id : '';
				$dataSet['customer_ip'] = !empty($request->customer_ip) ? $request->customer_ip : '';
				$dataSet['customer_user_agent'] = !empty($request->customer_user_agent) ? $request->customer_user_agent : '';
				$dataSet['video_type'] = !empty($request->video_type) ? $request->video_type : '';
				$dataSet['video_count'] = 1;

				$videoData = WatchLaterVideo::where(['customer_id' => $id, 'video_id' => $videoId])->first();

				if (!empty($videoData)) {
					$response->status(HttpOkay)->message('OPPS! This video is already added in list');
					return $response->send();
				} else {
					$res = WatchLaterVideo::create($dataSet);
					$response->status(HttpOkay)->message('Successfully added in list');
					return $response->send();
				}

			} catch (Throwable $exception) {
				$response->status(HttpServerError)->message($exception->getMessage());
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
			$videoData = WatchLaterVideo::where(['customer_id' => $cId, 'video_id' => $id])->first();

			if (!empty($videoData)) {
				$videoData->delete();

				$response->status(HttpOkay)->message('Successfully removed from list');
				return $response->send();
			} else {

				$response->status(HttpResourceNotFound)->message('OPPS! This video is not added in list');
				return $response->send();
			}

		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}

	}

	public function getWatchLaterVideo ()
	{
		$response = responseApp();
		try {

			$cId = $this->guard()->id();
			$dataSet = WatchLaterVideo::with('video')
				->where(['customer_id' => $cId])
				->get();

			$response->status(HttpOkay)->message('Success')->setValue('data', $dataSet);

		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}

	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}