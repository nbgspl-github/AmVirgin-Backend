<?php

namespace App\Http\Controllers\App\Customer\Playback;

use App\Models\Product;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TrailerPlayback extends PlaybackBase{
	public function __construct(){
		parent::__construct();
	}

	public function video(string $slug){
		try {
			$video = Video::where('slug', $slug)->firstOrFail();
			return $this->play($video->trailer, 'secured');
		}
		catch (ModelNotFoundException $exception) {
			return response()->json(['message' => 'No video found for that key.'], 404);
		}
	}

	public function series(string $slug){
		try {
			$video = Video::where('slug', $slug)->firstOrFail();
			return $this->play($video->trailer, 'secured');
		}
		catch (ModelNotFoundException $exception) {
			return response()->json(['message' => 'No tv-series found for that key.'], 404);
		}
	}

	public function product(string $slug){
		try {
			$product = Product::where('slug', $slug)->firstOrFail();
			return $this->play($product->trailer, 'secured');
		}
		catch (ModelNotFoundException $exception) {
			return response()->json(['message' => 'No product found for that key.'], 404);
		}
	}
}