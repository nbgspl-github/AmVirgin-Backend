<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\Subtitle\StoreRequest;

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
		)->with('languages',
			\App\Models\Video\Language::query()->get()
		)->with('video', $video);
	}


	public function store (StoreRequest $request, \App\Models\Video\Video $video) : \Illuminate\Http\RedirectResponse
	{
		$video->subtitles()->create($request->validated());
		return response()->redirectTo(
			route('admin.videos.edit.subtitle', $video->id)
		)->with('success',
			'Created subtitle source successfully.'
		);
	}

	/**
	 * @param \App\Models\Video\Video $video
	 * @param \App\Models\Models\Video\Subtitle $subtitle
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (\App\Models\Video\Video $video, \App\Models\Models\Video\Subtitle $subtitle) : \Illuminate\Http\JsonResponse
	{
		$subtitle->delete();
		return response()->json(
			[]
		);
	}
}