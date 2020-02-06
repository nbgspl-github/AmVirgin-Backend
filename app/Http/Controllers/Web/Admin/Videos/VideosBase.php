<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\MediaServer;
use App\Models\SubscriptionPlan;
use App\Models\Video;
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
			$payload = Video::retrieveThrows($id);
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

	public function store(){
		$response = responseWeb();
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
				'subscriptionType' => $payload['subscriptionType'],
				'price' => $payload['subscriptionType'] == SubscriptionPlan::Paid ? $payload['price'] : 0,
				'rank' => $payload['rank'],
			]);
			$response->success('Video details were saved successfully.');
		}
		catch (ValidationException $exception) {
			$response->data(request()->all())->back()->error($exception->getError());
		}
		catch (Throwable $exception) {
			$response->data(request()->all())->back()->error($exception->getMessage());
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}
}