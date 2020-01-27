<?php

namespace App\Http\Controllers\Web\Admin\TvSeries;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Interfaces\Directories;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\MediaServer;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AttributesController extends TvSeriesBase{
	public function create(){

	}

	public function edit($id){
		$response = responseWeb();
		try {
			$genrePayload = Genre::all();
			$languagePayload = MediaLanguage::all()->sortBy('name')->all();
			$serverPayload = MediaServer::all();
			$qualityPayload = MediaQuality::retrieveAll();
			$payload = Video::findOrFail($id);
			$response = view('admin.tv-series.attributes.edit')->
			with('payload', $payload)->
			with('genres', $genrePayload)->
			with('languages', $languagePayload)->
			with('servers', $serverPayload)->
			with('qualities', $qualityPayload);
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
			dd('ModelNotFound');
		}
		catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
			dd('Throwable');
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
		$response = responseWeb();
		$video = null;
		try {
			$video = Video::retrieveThrows($id);
			$validated = $this->requestValid(request(), $this->rules('update'));
			if (request()->has('trending')) {
				$this->replaceTrending($validated['rank']);
			}

			$validated = collect($validated)->filter()->all();

			if (request()->hasFile('trailer')) {
				Storage::disk('secured')->delete($video->getTrailer());
				$validated['trailer'] = Storage::disk('secured')->putFile(Directories::Trailers, request()->file('trailer'), 'public');
			}
			if (request()->hasFile('poster')) {
				Storage::disk('secured')->delete($video->getPoster());
				$validated['poster'] = Storage::disk('secured')->putFile(Directories::Posters, request()->file('poster'), 'public');
			}
			if (request()->hasFile('backdrop')) {
				Storage::disk('secured')->delete($video->getBackdrop());
				$validated['backdrop'] = Storage::disk('secured')->putFile(Directories::Backdrops, request()->file('backdrop'), 'public');
			}

			$validated['trending'] = request()->has('trending');
			$validated['showOnHome'] = request()->has('showOnHome');

			$video->update($validated);
			$response->success('Tv series details were successfully updated.')->route('admin.tv-series.index');
		}
		catch (ValidationException $exception) {
			$response->error($exception->getError())->back();
		}
		catch (Throwable $exception) {
			$response->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
}
