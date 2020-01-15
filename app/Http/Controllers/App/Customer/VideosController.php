<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Base\ResourceController;
use App\Http\Resources\Videos\VideoResource;
use App\Models\Video;
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
			$payload = new VideoResource(Video::where('slug', $slug)->firstOrFail());
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