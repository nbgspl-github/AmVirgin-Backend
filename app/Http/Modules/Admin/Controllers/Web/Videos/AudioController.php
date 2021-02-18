<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\Audio\StoreRequest;

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
		)->with('languages',
			\App\Models\Video\Language::query()->whereNotIn('id', $video->audios->pluck('video_language_id')->toArray())->get()
		)->with('video', $video);
	}

	public function store (StoreRequest $request, \App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		$video->audios()->create($request->validated());
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