<?php

namespace App\Http\Modules\Admin\Controllers\Web\News\Articles;

use App\Http\Modules\Admin\Requests\News\Article\Videos\StoreRequest;
use App\Http\Modules\Admin\Requests\News\Article\Videos\UpdateRequest;

class VideoController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function create () : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.news.articles.videos.create')->with('categories',
			\App\Models\News\Category::query()->get(['id', 'name'])
		);
	}

	public function edit (\App\Models\News\Article $article) : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.news.articles.videos.edit')->with('article', $article)->with('categories',
			\App\Models\News\Category::query()->get(['id', 'name'])
		);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\JsonResponse
	{
		$article = \App\Models\News\Article::query()->create($request->validated());
		$this->updateDuration($article);
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Created video article successfully.'
		);
	}

	public function update (UpdateRequest $request, \App\Models\News\Article $article) : \Illuminate\Http\JsonResponse
	{
		$article->update($request->validated());
		if ($request->has('video'))
			$this->updateDuration($article);
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Updated video article successfully.'
		);
	}

	protected function updateDuration ($article)
	{
		$media = \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('secured')->open($article->getRawOriginal('video'));
		$seconds = $media->getDurationInSeconds();
		$article->update([
			'duration' => \App\Library\Utils\Extensions\Time::toDuration($seconds, "%02d:%02d:%02d")
		]);
	}
}