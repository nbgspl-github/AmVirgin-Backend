<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\VideoTypes;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\MediaServer;
use App\Models\SubscriptionPlan;
use App\Models\Video;
use App\Models\VideoMeta;
use App\Models\VideoSource;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class VideosBase extends BaseController{
	use FluentResponse;
	use ValidatesRequest;

	public function __construct(){
		parent::__construct();
		$this->ruleSet->load('rules.admin.videos');
	}

	public function index(){
		$videos = Video::where('hasSeasons', false)->get();
		return view('admin.videos.index')->with('videos', $videos);
	}

	public function choose($id){
		$response = responseWeb();
		try {
			$payload = Video::where(['id' => $id, 'hasSeasons' => false])->firstOrFail();
			$response = view('admin.videos.edit.choices')->with('payload', $payload);
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.videos.index')->error('Could not find video for that key.');
		}
		catch (Throwable $exception) {
			$response->route('admin.videos.index')->error($exception->getMessage());
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function create(){
		$genres = Genre::all();
		$languages = MediaLanguage::all()->sortBy('name')->all();
		$servers = MediaServer::all();
		$quality = MediaQuality::retrieveAll();
		return view('admin.videos.create')->
		with('genres', $genres)->
		with('languages', $languages)->
		with('servers', $servers)->
		with('qualities', $quality);
	}

	public function show($slug){
		$video = null;
		try {
			$video = Video::where('slug', $slug)->where('hasSeasons', true)->firstOrFail();
			return jsonEncode($video);
		}
		catch (ModelNotFoundException $exception) {
			return $exception->getMessage();
		}
		catch (Throwable $exception) {
			return $exception->getMessage();
		}
	}

	public function store(){
		$response = $this->response();
		try {
			$payload = $this->requestValid(request(), $this->rules('store'));
			$video = Video::create([
				'title' => $payload['title'],
				'description' => $payload['description'],
				'duration' => $payload['duration'],
				'released' => $payload['released'],
				'cast' => $payload['cast'],
				'director' => $payload['director'],
				'genreId' => $payload['genreId'],
				'rating' => $payload['rating'],
				'pgRating' => $payload['pgRating'],
				'type' => VideoTypes::Movie,
				'hits' => 0,
				'trending' => request()->has('trending'),
				'rank' => $payload['rank'],
				'showOnHome' => request()->has('showOnHome'),
				'subscriptionType' => $payload['subscriptionType'],
				'price' => $payload['subscriptionType'] == SubscriptionPlan::Paid ? $payload['price'] : 0,
				'hasSeasons' => false,
			]);
			$response->setValue('route', route('admin.videos.edit.action', $video->getKey()))->message('Video details were saved successfully.');
		}
		catch (ValidationException $exception) {
			$response->message($exception->getError())->status(HttpInvalidRequestFormat);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function delete($id, $subId = null){
		$tvSeries = null;
		$response = $this->response();
		try {
			$tvSeries = Video::findOrFail($id);
			$meta = VideoMeta::where('videoId', $tvSeries->getKey())->get();
			$meta->each(function (VideoMeta $meta){
				$meta->delete();
			});
			$sources = VideoSource::where('videoId', $tvSeries->getKey())->get();
			$sources->each(function (VideoSource $videoSource){
				$videoSource->delete();
			});
			$tvSeries->delete();
			$response->setValue('code', 200)->message('Successfully deleted video.');
		}
		catch (ModelNotFoundException $exception) {
			$response->setValue('code', 400)->message('Could not find video for that key.');
		}
		catch (Throwable $exception) {
			$response->setValue('code', 500)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function replaceTrending($chosenRank){
		$ranked = Video::where('rank', $chosenRank)->first();
		if (!null($ranked)) {
			$ranked->rank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}
}