<?php

namespace App\Http\Controllers\Api\Customer\Playback;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Storage;

class PlaybackBase extends BaseController
{
	public function __construct ()
	{
		parent::__construct();
	}

	protected function play (string $path, string $disk = 'secured')
	{
		$pathX = Storage::disk($disk)->path($path);
		if (Storage::disk($disk)->exists($path)) {
			$pathX = str_replace('\\', '/', $pathX);
			return Storage::disk($disk)->url($path);
		} else {
			return response()->json(['message' => 'No playable media found.'], 404);
		}
	}
}