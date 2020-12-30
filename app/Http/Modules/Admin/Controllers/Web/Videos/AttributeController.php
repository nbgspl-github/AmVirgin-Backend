<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\Attributes\UpdateRequest;
use App\Models\Video\Video;

class AttributeController extends \App\Http\Modules\Admin\Controllers\Web\WebController
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

	public function edit (Video $video)
	{
		return view('admin.videos.attributes.edit')->with('payload', $video);
	}

	public function update (UpdateRequest $request, Video $video) : \Illuminate\Http\RedirectResponse
	{
		$video->update($request->validated());
		return response()->redirectTo(
			route('admin.videos.index')
		)->with('success', 'Attributes updated successfully.');
	}
}