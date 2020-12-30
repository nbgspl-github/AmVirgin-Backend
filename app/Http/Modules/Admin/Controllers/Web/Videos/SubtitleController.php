<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

class SubtitleController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index (\App\Models\Video\Video $video)
	{
		return view('admin.videos.subtitle.index')->with('subtitles',
			$video->subtitles()->latest()->paginate($this->paginationChunk())
		);
	}
}