<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Library\Enums\Common\PageSectionType;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\MediaServer;
use App\Models\PageSection;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class AttributesController extends VideosBase
{
	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.videos.attributes');
	}

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$payload = Video::findOrFail($id);
			$genrePayload = Genre::all();
			$languagePayload = MediaLanguage::all()->sortBy('name')->all();
			$serverPayload = MediaServer::all();
			$qualityPayload = MediaQuality::all();
			$sectionsPayload = PageSection::where('type', PageSectionType::Entertainment)->get();
			$response = view('admin.videos.attributes.edit')->
			with('payload', $payload)->
			with('genres', $genrePayload)->
			with('languages', $languagePayload)->
			with('servers', $serverPayload)->
			with('qualities', $qualityPayload)->
			with('sections', $sectionsPayload);
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

	public function update ($id)
	{
		$response = responseWeb();
		try {
			$video = Video::findOrFail($id);
			$validated = $this->requestValid(request(), $this->rules('update'));
			if (request()->has('trending')) {
				$this->replaceTrending($validated['rank']);
			}

			$validated = collect($validated)->filter()->all();

			$validated['trending'] = request()->has('trending');
			$validated['showOnHome'] = request()->has('showOnHome');

			$video->update($validated);
			$response->success('Video details were successfully updated.')->route('admin.videos.index');
		} catch (ValidationException $exception) {
			$response->error($exception->getError())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}