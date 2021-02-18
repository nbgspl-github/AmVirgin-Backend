<?php

namespace App\Http\Modules\Admin\Controllers\Web;

class PlaybackController extends WebController
{
	public function index ()
	{
		return view('admin.playback.index');
	}
}