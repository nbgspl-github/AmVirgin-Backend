<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Http\Modules\Admin\Requests\Users\Series\Source\UpdateRequest;

class SourceController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index (\App\Models\Video\Video $video)
	{
		return view('admin.tv-series.source.index')->with(
			'sources', $video->sources()->orderBy('season')->orderBy('episode')->paginate($this->paginationChunk()))->with(
			'languages', \App\Models\Video\Language::query()->get()
		)->with('video', $video);
	}

	public function edit (\App\Models\Video\Video $video)
	{
		return view('admin.videos.source.edit')->with('video', $video);
	}

	public function store (UpdateRequest $request, \App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		$source = $video->sources()->create($request->validated());
		\App\Jobs\FillVideoMetadata::dispatchNow($source);
		\App\Jobs\TranscoderTask::dispatch($source)->delay(now()->addMinutes(5));
		return response()->json([
			'message' => 'Created episode successfully.'
		]);
	}
}