<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Exceptions\ValidationException;
use App\Http\Modules\Admin\Repository\Videos\VideoRepositoryInterface;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Enums\Common\PageSectionType;
use App\Library\Enums\Videos\Types;
use App\Library\Http\WebResponse;
use App\Models\PageSection;
use App\Models\SubscriptionPlan;
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

class VideosBase extends BaseController
{
	use FluentResponse;
	use ValidatesRequest;

	/**
	 * @var VideoRepositoryInterface
	 */
	protected $repository;

	public function __construct (VideoRepositoryInterface $repository)
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->ruleSet->load('rules.admin.videos');
		$this->repository = $repository;
	}

	public function index ()
	{
		return view('admin.videos.index')->with('videos', $this->repository->allMoviesPaginated());
	}

	public function choose ($id)
	{
		$response = responseWeb();
		try {
			$payload = Video::where(['id' => $id, 'hasSeasons' => false])->firstOrFail();
			$response = view('admin.videos.edit.choices')->with('payload', $payload);
		} catch (ModelNotFoundException $exception) {
			$response->route('admin.videos.index')->error('Could not find video for that key.');
		} catch (Throwable $exception) {
			$response->route('admin.videos.index')->error($exception->getMessage());
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function create ()
	{
		$genres = Genre::all();
		$languages = MediaLanguage::all()->sortBy('name')->all();
		$servers = MediaServer::all();
		$quality = MediaQuality::all();
		$sections = PageSection::where('type', PageSectionType::Entertainment)->get();
		return view('admin.videos.create')->
		with('genres', $genres)->
		with('languages', $languages)->
		with('servers', $servers)->
		with('qualities', $quality)->
		with('sections', $sections);
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

	public function store ()
	{
		$response = responseApp();
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
				'sectionId' => $payload['sectionId'],
				'rating' => $payload['rating'],
				'pgRating' => $payload['pgRating'],
				'type' => Types::Movie,
				'hits' => 0,
				'trending' => request()->has('trending'),
				'rank' => $payload['rank'],
				'showOnHome' => request()->has('showOnHome'),
				'subscriptionType' => $payload['subscriptionType'],
				'price' => $payload['subscriptionType'] == SubscriptionPlan::Paid ? $payload['price'] : 0,
				'hasSeasons' => false,
			]);
			$video->pending = false;
			$video->save();
			$response->setValue('route', route('admin.videos.edit.action', $video->getKey()))->message('Video details were saved successfully.');
		} catch (ValidationException $exception) {
			$response->message($exception->getError())->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST);
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->error($exception->getMessage());
		} finally {
			return $response->send();
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
			$response->setValue('code', 200)->message('Successfully deleted video.');
		} catch (ModelNotFoundException $exception) {
			$response->setValue('code', 400)->message('Could not find video for that key.');
		} catch (Throwable $exception) {
			$response->setValue('code', 500)->message($exception->getMessage());
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