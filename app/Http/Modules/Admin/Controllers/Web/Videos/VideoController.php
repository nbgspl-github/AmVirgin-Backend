<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\StoreRequest;
use App\Library\Enums\Videos\Types;
use App\Models\Video\Video;

class VideoController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	/**
	 * @var Video
	 */
	protected $model;

	public function __construct ()
	{
		parent::__construct();
		$this->model = app(Video::class);
	}

	public function index ()
	{
		return view('admin.videos.index')->with('videos',
			$this->model->newQuery()->where('type', Types::Movie)->paginate($this->paginationChunk())
		);
	}

	public function choose (Video $video)
	{
		return view('admin.videos.edit.choices')->with('payload', $video)->with('queues', $video->queues()->latest()->get());
	}

	public function create ()
	{
		return view('admin.videos.create');
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		$video = $this->model->newQuery()->create($request->validated());
		return response()->redirectTo(route('admin.videos.edit.action', $video->id))->with(
			'success', 'Video details were successfully saved. Please proceed to next step.'
		);
	}

	/**
	 * @param Video $video
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function destroy (Video $video) : \Illuminate\Http\JsonResponse
	{
		$video->snaps()->each(fn (\App\Models\Video\Snap $snap) => $snap->delete());
		$video->sources()->delete();
		$video->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_NO_CONTENT,
		);
	}
}