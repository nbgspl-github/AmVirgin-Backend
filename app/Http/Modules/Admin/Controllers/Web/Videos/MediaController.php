<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\Media\UpdateRequest;
use App\Models\Video\Video;

class MediaController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function edit (Video $video)
	{
		return view('admin.videos.media.edit')->with('payload', $video);
	}

	public function update (UpdateRequest $request, Video $video) : \Illuminate\Http\JsonResponse
	{
		$video->update($request->validated());
		return response()->json([
			'status' => \Illuminate\Http\Response::HTTP_OK,
			'message' => 'Successfully uploaded/updated media for video.'
		]);
	}
}