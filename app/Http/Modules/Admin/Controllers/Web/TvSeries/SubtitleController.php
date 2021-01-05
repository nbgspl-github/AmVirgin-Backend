<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Http\Modules\Admin\Requests\Users\Series\Subtitle\StoreRequest;

class SubtitleController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index (\App\Models\Video\Video $video, \App\Models\Video\Source $source)
	{
		return view('admin.tv-series.subtitle.index')->with('video', $video)->with('source', $source)->with('subtitles', $source->subtitles()->paginate($this->paginationChunk()))->with('languages',
			\App\Models\Video\Language::query()->get()
		);
	}

	public function store (StoreRequest $request, \App\Models\Video\Video $video, \App\Models\Video\Source $source) : \Illuminate\Http\JsonResponse
	{
		$source->subtitles()->create($request->validated());
		return response()->json([
			'message' => 'Created subtitle source successfully.'
		]);
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