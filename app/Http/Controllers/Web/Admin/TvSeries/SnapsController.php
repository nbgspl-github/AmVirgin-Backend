<?php

namespace App\Http\Controllers\Web\Admin\TvSeries;

use App\Classes\WebResponse;
use App\Models\Video;
use App\Models\VideoSnap;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class SnapsController extends TvSeriesBase{
	public function create(){

	}

	public function edit($id){
		$response = responseWeb();
		try {
			$payload = Video::findOrFail($id);
			$snaps = $payload->snaps()->get()->toArray();
			$response = view('admin.tv-series.snaps.edit')->
			with('payload', $payload)->
			with('snaps', $snaps)->
			with('template', view('admin.tv-series.snaps.imageBox')->render());
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
		}
		catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function store(){

	}

	public function update($id){
		try {

		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}

	public function delete(...$id){
		$response = $this->response();
		try {
			$videoSnap = VideoSnap::retrieveThrows($id[1]);
			$videoSnap->delete();
			$response->status(HttpOkay)->message('Snapshot deleted successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find snapshot for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
}