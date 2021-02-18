<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Http\Modules\Admin\Requests\Users\Series\StoreRequest;
use App\Library\Enums\Videos\Types;
use App\Models\Video\Video;

class TvSeriesController extends \App\Http\Modules\Admin\Controllers\Web\WebController
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
		return view('admin.tv-series.index')->with(
			'series', $this->model->newQuery()->where('type', Types::Series)->paginate($this->paginationChunk())
		);
	}

	public function choose (Video $video)
	{
		return view('admin.tv-series.edit.choices')->with('payload', $video);
	}

	public function create ()
	{
		return view('admin.tv-series.create');
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		$video = $this->model->newQuery()->create($request->validated());
		return response()->redirectTo(route('admin.tv-series.edit.action', $video->id))->with(
			'success', 'Tv series details were successfully saved. Please proceed to next step.'
		);
	}

	/**
	 * @param Video $video
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (Video $video) : \Illuminate\Http\JsonResponse
	{
		$video->snaps()->delete();
		$video->sources()->delete();
		$video->audios()->delete();
		$video->subtitles()->delete();
		$video->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_NO_CONTENT,
		);
	}
}