<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Http\Controllers\Web\Admin\TvSeries\TvSeriesBase;
use App\Interfaces\Directories;
use App\Models\Video;
use App\Storage\SecuredDisk;
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
			$video = Video::retrieveThrows($id);
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
		$response = $this->response();
		try {
			$video = Video::retrieveThrows($id);
			$this->requestValid(request(), $this->rules('update'));
			if (request()->hasFile('poster')) {
				if (SecuredDisk::access()->exists($video->getPoster())) {
					SecuredDisk::access()->delete($video->getPoster());
				}
				$video->setPoster(SecuredDisk::access()->putFile(Directories::Posters, request()->file('poster'), 'private'));
			}

			if (request()->hasFile('backdrop')) {
				if (SecuredDisk::access()->exists($video->getBackdrop())) {
					SecuredDisk::access()->delete($video->getBackdrop());
				}
				$video->setBackdrop(SecuredDisk::access()->putFile(Directories::Backdrops, request()->file('backdrop'), 'private'));
			}

			if (request()->hasFile('trailer')) {
				if (SecuredDisk::access()->exists($video->getTrailer())) {
					SecuredDisk::access()->delete($video->getTrailer());
				}
				$video->setTrailer(SecuredDisk::access()->putFile(Directories::Trailers, request()->file('trailer'), 'private'));
			}
			$video->save();
			$response->status(HttpOkay)->message('Successfully uploaded/updated media for video.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find video for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}