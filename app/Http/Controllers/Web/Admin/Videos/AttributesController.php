<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\MediaServer;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class AttributesController extends VideosBase{
	public function __construct(){
		parent::__construct();
	}

	public function edit($id){
		$response = responseWeb();
		try {
			$payload = Video::retrieveThrows($id);
			$genrePayload = Genre::all();
			$languagePayload = MediaLanguage::all()->sortBy('name')->all();
			$serverPayload = MediaServer::all();
			$qualityPayload = MediaQuality::retrieveAll();
			$response = view('admin.videos.attributes.edit')->
			with('payload', $payload)->
			with('genres', $genrePayload)->
			with('languages', $languagePayload)->
			with('servers', $serverPayload)->
			with('qualities', $qualityPayload);
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.videos.index')->error('Could not find video for that key.');
		}
		catch (Throwable $exception) {
			$response->route('admin.videos.index')->error($exception->getMessage());
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}
}