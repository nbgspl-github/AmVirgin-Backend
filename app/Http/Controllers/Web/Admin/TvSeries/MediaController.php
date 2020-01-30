<?php

namespace App\Http\Controllers\Web\Admin\TvSeries;

use App\Classes\WebResponse;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class MediaController extends TvSeriesBase{
	public function create(){

	}

	public function edit($id){
		$response = responseWeb();
		try {
			$tvSeries = Video::retrieveThrows($id);
			$response = view('admin.tv-series.media.edit')->with('payload', $tvSeries);
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

	}
}