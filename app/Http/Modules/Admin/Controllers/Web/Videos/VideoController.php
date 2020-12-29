<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\StoreRequest;
use App\Library\Enums\Common\PageSectionType;
use App\Library\Enums\Videos\Types;
use App\Models\Section;
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
		return view('admin.videos.edit.choices')->with('payload', $video);
	}

	public function create ()
	{
		return view('admin.videos.create')->with('sections',
			Section::query()->where('type', PageSectionType::Entertainment)->get()
		);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\JsonResponse
	{
		$video = $this->model->newQuery()->create($request->validated());
		return responseApp()->setValue(
			'route', route('admin.videos.edit.action', $video->getKey())
		)->send();
	}

	/**
	 * @param Video $video
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function destroy (Video $video) : \Illuminate\Http\JsonResponse
	{
		dd(request()->ajax() ? "Yes ajax" : 'No ajax');
//		$video->snaps()->delete();
//		$video->sources()->delete();
//		$video->delete();
		return responseApp()->prepare(
			[],
			\Illuminate\Http\Response::HTTP_NO_CONTENT,
		);
	}

	protected function replaceTrending ($chosenRank)
	{
		$ranked = Video::where('rank', $chosenRank)->first();
		if (!is_null($ranked)) {
			$ranked->rank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}
}