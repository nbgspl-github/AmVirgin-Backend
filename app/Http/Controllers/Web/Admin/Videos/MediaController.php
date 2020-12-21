<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Library\Enums\Common\Directories;
use App\Library\Utils\Uploads;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class MediaController extends VideosBase
{
	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.videos.media');
	}

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$video = Video::findOrFail($id);
			$response = view('admin.videos.media.edit')->with('payload', $video);
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
		$response = responseApp();
		try {
			$video = Video::findOrFail($id);
			$this->requestValid(request(), $this->rules('update'));
			if (request()->hasFile('poster')) {
				if (Uploads::access()->exists($video->getPoster())) {
					Uploads::access()->delete($video->getPoster());
				}
				$video->setPoster(Uploads::access()->putFile(Directories::Posters, request()->file('poster'), 'private'));
			}

			if (request()->hasFile('backdrop')) {
				if (Uploads::access()->exists($video->getBackdrop())) {
					Uploads::access()->delete($video->getBackdrop());
				}
				$video->setBackdrop(Uploads::access()->putFile(Directories::Backdrops, request()->file('backdrop'), 'private'));
			}

			if (request()->hasFile('trailer')) {
				if (Uploads::access()->exists($video->getTrailer())) {
					Uploads::access()->delete($video->getTrailer());
				}
				$video->setTrailer(Uploads::access()->putFile(Directories::Trailers, request()->file('trailer'), 'private'));
			}
			$video->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Successfully uploaded/updated media for video.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find video for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}