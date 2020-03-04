<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Str;
use App\Http\Controllers\Base\ResourceController;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Http\Resources\Videos\VideoResource;
use App\Interfaces\VideoTypes;
use App\Models\Video;
use App\Models\VideoSource;
use App\Storage\SecuredDisk;
use App\Traits\FluentResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Throwable;

class VideosController extends ExtendedResourceController {
	public function __construct() {
		parent::__construct();
	}

	public function show($id) {
		$response = responseApp();
		try {
			$video = Video::retrieveThrows($id);
			$payload = new VideoResource($video);
			$payload = $payload->jsonSerialize();
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
			}
			$response->status(HttpOkay)->message('Success')->setValue('data', $payload);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('No video/tv-series found for given key.')->setValue('data');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage())->setValue('data');
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return auth('customer-api');
	}
}