<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Models\Video\Video;

class MediaController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function edit (Video $video)
	{
		return view('admin.videos.media.edit')->with('video', $video);
	}

	public function update (\App\Http\Modules\Admin\Requests\Videos\Media\UpdateRequest $request, Video $video) : \Illuminate\Http\JsonResponse
	{
		$video->update($request->validated());
		return responseApp()->prepare(
			['route' => route('admin.videos.edit.action', $video->id)], \Illuminate\Http\Response::HTTP_OK, 'Video media updated successfully.'
		);
	}
}