<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\Snapshots\UpdateRequest;
use App\Models\Video\Snap;
use App\Models\Video\Video;

class SnapController extends VideoController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function edit (Video $video)
	{
		return view('admin.videos.snaps.edit')->with('payload', $video)->with('snaps', $video->snaps)->
		with('template', view('admin.videos.snaps.imageBox')->render());
	}

	public function update (UpdateRequest $request, Video $video) : \Illuminate\Http\JsonResponse
	{
		collect($request->file('image'))->each(function (\Illuminate\Http\UploadedFile $file) use (&$video) {
			$video->snaps()->create([
				'file' => $file
			]);
		});
		return response()->json([
			'status' => \Illuminate\Http\Response::HTTP_OK,
			'message' => 'Snapshots updated successfully.'
		]);
	}

	/**
	 * @param Video $video
	 * @param Snap $snap
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (Video $video, Snap $snap) : \Illuminate\Http\JsonResponse
	{
		$snap->delete();
		return responseApp()->prepare(
			null,
			\Illuminate\Http\Response::HTTP_OK,
			'Snapshot deleted successfully.'
		);
	}
}