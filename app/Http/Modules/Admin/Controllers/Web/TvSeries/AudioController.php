<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Http\Modules\Admin\Requests\Users\Series\Audio\StoreRequest;

class AudioController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index (\App\Models\Video\Video $video, \App\Models\Video\Source $source)
	{
		return view('admin.tv-series.audio.index')->with('video', $video)->with('source', $source)->with('audios', $source->audios()->paginate($this->paginationChunk()))->with('languages',
			\App\Models\Video\Language::query()->whereNotIn('id', $source->audios->pluck('video_language_id')->toArray())->get()
		);
	}

	public function store (StoreRequest $request, \App\Models\Video\Video $video, \App\Models\Video\Source $source) : \Illuminate\Http\JsonResponse
	{
		$source->audios()->create($request->validated());
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Audio track created successfully.'
		);
	}

	/**
	 * @param \App\Models\Video\Video $video
	 * @param \App\Models\Models\Video\Audio $audio
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (\App\Models\Video\Video $video, \App\Models\Models\Video\Audio $audio) : \Illuminate\Http\JsonResponse
	{
		$audio->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Audio track deleted successfully.'
		);
	}
}