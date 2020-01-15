<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Base\ResourceController;
use App\Http\Resources\Videos\VideoResource;
use App\Interfaces\VideoTypes;
use App\Models\Video;
use App\Models\VideoSource;
use App\Traits\FluentResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Throwable;

class VideosController extends ResourceController{
	use FluentResponse;

	public function show($slug){
		$response = $this->response();
		try {
			/**
			 * @var Video $video
			 */
			$video = Video::where('slug', $slug)->firstOrFail();
			$payload = new VideoResource($video);
			$payload = $payload->jsonSerialize();
			if ($video->getType() == VideoTypes::Series) {
				$seasons = $video->sources()->get()->groupBy('season')->transform(function (Collection $season){
					return $season->groupBy('episode')->transform(function (Collection $episode){
						return [
							'title' => $episode->first()->getTitle(),
							'description' => $episode->first()->getDescription(),
							'options' => $episode->transform(function (VideoSource $source){
								return [
									'language' => $source->language()->first()->getName(),
									'quality' => $source->mediaQuality()->first()->getName(),
									'url' => $source->getFile(),
									'subtitle' => $source->getSubtitle(),
								];
							})->values(),
						];
					})->values();
				})->values();
			}
			$response->status(HttpOkay)->message('Success')->setValue('data', $seasons);
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

	protected function parentProvider(){

	}

	protected function provider(){

	}

	protected function resourceConverter(Model $model){

	}

	protected function collectionConverter(Collection $collection){

	}

	protected function guard(){

	}
}