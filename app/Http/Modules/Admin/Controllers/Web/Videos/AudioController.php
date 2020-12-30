<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

class AudioController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index (\App\Models\Video\Video $video)
	{
		return view('admin.videos.audio.index')->with('audios',
			$video->audios()->latest()->paginate($this->paginationChunk())
		);
	}
}