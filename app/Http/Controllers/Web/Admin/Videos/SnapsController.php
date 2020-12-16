<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\Admin\TvSeries\TvSeriesBase;
use App\Interfaces\Directories;
use App\Models\Video;
use App\Models\VideoSnap;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Throwable;

class SnapsController extends VideosBase
{
	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.videos.snaps');
	}

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$payload = Video::findOrFail($id);
			$snaps = $payload->snaps()->get()->toArray();
			$response = view('admin.videos.snaps.edit')->
			with('payload', $payload)->
			with('snaps', $snaps)->
			with('template', view('admin.videos.snaps.imageBox')->render());
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
		$response = $this->responseApp();
		try {
			$video = Video::retrieveThrows($id);
			$payload = $this->requestValid(request(), $this->rules('store'));
			collect($payload['image'])->each(function (UploadedFile $file) use ($video) {
				VideoSnap::newObject()->
				setVideoId($video->getKey())->
				setFile(SecuredDisk::access()->putFile(Directories::VideoSnaps, $file, 'private'))->
				setDescription('Special snaps')->
				save();
			});
			$response->status(HttpOkay)->message('Snapshots uploaded/updated successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find video for that key.');
		} catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete ($id, $subId = null)
	{
		$response = $this->responseApp();
		try {
			$videoSnap = VideoSnap::retrieveThrows($subId);
			$videoSnap->delete();
			$response->status(HttpOkay)->message('Snapshot deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find snapshot for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}