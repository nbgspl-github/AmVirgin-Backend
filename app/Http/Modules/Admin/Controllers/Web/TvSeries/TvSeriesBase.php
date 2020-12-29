<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Enums\Common\PageSectionType;
use App\Library\Enums\Videos\Types;
use App\Library\Http\WebResponse;
use App\Models\Section;
use App\Models\Video\Genre;
use App\Models\Video\MediaLanguage;
use App\Models\Video\MediaQuality;
use App\Models\Video\MediaServer;
use App\Models\Video\Meta;
use App\Models\Video\Source;
use App\Models\Video\Video;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class TvSeriesBase extends BaseController
{
	use FluentResponse;
	use ValidatesRequest;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.tv-series');
	}

	public function index ()
	{
		$series = Video::where('hasSeasons', true)->get();
		return view('admin.tv-series.index')->with('series', $series);
	}

	public function edit ($id)
	{
		$type = request('type');
		if ($type == 'attributes') {
			return $this->editAttributes($id);
		} else {
			return $this->editContent($id);
		}
	}

	public function choose ($id)
	{
		$response = responseWeb();
		try {
			$payload = Video::where(['id' => $id, 'hasSeasons' => true])->firstOrFail();
			$response = view('admin.tv-series.edit.choices')->with('payload', $payload);
		} catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
		} catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function create ()
	{
		$genrePayload = Genre::all();
		$languagePayload = MediaLanguage::all()->sortBy('name')->all();
		$serverPayload = MediaServer::all();
		$qualityPayload = MediaQuality::all();
		$sections = Section::where('type', PageSectionType::Entertainment)->get();
		return view('admin.tv-series.create')->
		with('genres', $genrePayload)->
		with('languages', $languagePayload)->
		with('servers', $serverPayload)->
		with('qualities', $qualityPayload)->
		with('sections', $sections);
	}

	public function store ()
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules('store'));
			if (request()->has('trending')) {
				$this->replaceTrending($validated['rank']);
			}
			$validated = collect($validated)->filter()->all();
			$video = Video::create([
				'title' => $validated['title'],
				'description' => $validated['description'],
				'duration' => $validated['duration'],
				'released' => $validated['released'],
				'cast' => $validated['cast'],
				'director' => $validated['director'],
				'genreId' => $validated['genreId'],
				'sectionId' => $validated['sectionId'],
				'rating' => $validated['rating'],
				'pgRating' => $validated['pgRating'],
				'type' => Types::Series,
				'hits' => 0,
				'trending' => request()->has('trending'),
				'rank' => is_null($validated['rank']) ? 0 : $validated['rank'],
				'showOnHome' => request()->has('showOnHome'),
				'subscriptionType' => $validated['subscriptionType'],
				'price' => isset($validated['price']) ? $validated['price'] : 0.00,
				'hasSeasons' => true,
			]);
			$video->save();
			$response = $this->success()->message('Tv series details were successfully saved. Please proceed to next step.')->setValue('route', route('admin.tv-series.edit.action', $video->getKey()));
		} catch (ValidationException $exception) {
			$response = $this->failed()->message($exception->getError())->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST);
		} catch (Throwable $exception) {
			$response = $this->error()->message($exception->getTraceAsString());
		} finally {
			return $response->send();
		}
	}

	public function show ($slug)
	{
		$video = null;
		try {
			$video = Video::where('slug', $slug)->where('hasSeasons', true)->firstOrFail();
			return jsonEncode($video);
		} catch (ModelNotFoundException $exception) {
			return $exception->getMessage();
		} catch (Throwable $exception) {
			return $exception->getMessage();
		}
	}

	public function delete ($id, $subId = null)
	{
		$tvSeries = null;
		$response = responseApp();
		try {
			$tvSeries = Video::findOrFail($id);
			$meta = Meta::where('videoId', $tvSeries->getKey())->get();
			$meta->each(function (Meta $meta) {
				$meta->delete();
			});
			$sources = Source::where('videoId', $tvSeries->getKey())->get();
			$sources->each(function (Source $videoSource) {
				$videoSource->delete();
			});
			$tvSeries->delete();
			$response->setValue('code', 200)->message('Successfully deleted tv series.');
		} catch (ModelNotFoundException $exception) {
			$response->setValue('code', 400)->message('Could not find tv series for that key.');
		} catch (Throwable $exception) {
			$response->setValue('code,500')->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function replaceTrending ($chosenRank)
	{
		$ranked = Video::where('rank', $chosenRank)->first();
		if (!is_null($ranked)) {
			$ranked->rank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}
}