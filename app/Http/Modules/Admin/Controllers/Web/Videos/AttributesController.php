<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Repository\Videos\Contracts\VideoRepository;
use App\Library\Enums\Common\PageSectionType;
use App\Models\Section;
use App\Models\Video\Genre;
use App\Models\Video\MediaLanguage;
use App\Models\Video\MediaQuality;
use App\Models\Video\MediaServer;
use App\Models\Video\Video;

class AttributesController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	/**
	 * @var VideoRepository
	 */
	protected $repository;

	public function __construct (VideoRepository $repository)
	{
		parent::__construct();
		$this->repository = $repository;
		$this->ruleSet->load('rules.admin.videos.attributes');
	}

	public function edit (Video $video)
	{
		$genres = Genre::all();
		$languages = MediaLanguage::all()->sortBy('name')->all();
		$serverPayload = MediaServer::all();
		$qualityPayload = MediaQuality::all();
		$sectionsPayload = Section::where('type', PageSectionType::Entertainment)->get();
		return view('admin.videos.attributes.edit')->
		with('payload', $video)->
		with('genres', $genres)->
		with('languages', $languages)->
		with('servers', $serverPayload)->
		with('qualities', $qualityPayload)->
		with('sections', $sectionsPayload);
	}

	public function update (Video $video)
	{
//		$response = responseWeb();
//		try {
//			$video = Video::findOrFail($id);
//			$validated = $this->requestValid(request(), $this->rules('update'));
//			if (request()->has('trending')) {
//				$this->replaceTrending($validated['rank']);
//			}
//
//			$validated = collect($validated)->filter()->all();
//
//			$validated['trending'] = request()->has('trending');
//			$validated['showOnHome'] = request()->has('showOnHome');
//
//			$video->update($validated);
//			$response->success('Video details were successfully updated.')->route('admin.videos.index');
//		} catch (ValidationException $exception) {
//			$response->error($exception->getError())->back();
//		} catch (Throwable $exception) {
//			$response->error($exception->getMessage());
//		} finally {
//			return $response->send();
//		}
	}
}