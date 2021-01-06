<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\Source\UpdateRequest;

class SourceController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function edit (\App\Models\Video\Video $video)
	{
		return view('admin.videos.source.edit')->with('video', $video);
	}

	public function update (UpdateRequest $request, \App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		/**
		 * @var $source \App\Models\Video\Source
		 */
		$source = $video->sources->first();
		if ($source != null) {
			$source->update($request->validated());
		} else {
			$source = $video->sources()->create($request->validated());
		}
		\App\Jobs\TranscoderTask::dispatch($source)->delay(now()->addMinutes(5));
		return response()->json([
			'message' => 'Created video source successfully.'
		]);
	}
}