<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Library\Enums\Common\Directories;
use App\Library\Http\WebResponse;
use App\Library\Utils\Uploads;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class MediaController extends TvSeriesBase
{
	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.tv-series.media');
	}

	public function create ()
	{

	}

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$tvSeries = Video::findOrFail($id);
			$response = view('admin.tv-series.media.edit')->with('payload', $tvSeries);
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

	public function store ()
	{

	}

	public function update ($id)
	{
		$response = responseApp();
		try {
			$tvSeries = Video::findOrFail($id);
			$this->requestValid(request(), $this->rules('update'));
			if (request()->hasFile('poster')) {
				if (Uploads::access()->exists($tvSeries->getPoster())) {
					Uploads::access()->delete($tvSeries->getPoster());
				}
				$tvSeries->setPoster(Uploads::access()->putFile(Directories::Posters, request()->file('poster')));
			}

			if (request()->hasFile('backdrop')) {
				if (Uploads::access()->exists($tvSeries->getBackdrop())) {
					Uploads::access()->delete($tvSeries->getBackdrop());
				}
				$tvSeries->setBackdrop(Uploads::access()->putFile(Directories::Backdrops, request()->file('backdrop')));
			}

			if (request()->hasFile('trailer')) {
				if (Uploads::access()->exists($tvSeries->getTrailer())) {
					Uploads::access()->delete($tvSeries->getTrailer());
				}
				$tvSeries->setTrailer(Uploads::access()->putFile(Directories::Trailers, request()->file('trailer')));
			}
			$tvSeries->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Successfully uploaded/updated media for tv series.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find tv series for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}