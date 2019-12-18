<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Base\ResourceController;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class VideosController extends ResourceController{

	public function index(){
		$payload = Video::retrieveAll();
		return view('admin.videos.index')->with('videos', $payload);
	}

	protected function parentProvider(){
		return null;
	}

	protected function provider(){
		return Video::class;
	}

	protected function resourceConverter(Model $model){
		return
	}

	protected function collectionConverter(Collection $collection){

	}

	protected function guard(){
		return Auth::guard('seller-api');
	}
}