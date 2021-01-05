<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Http\Modules\Admin\Requests\Users\Series\Attributes\UpdateRequest;
use App\Models\Video\Video;

class AttributeController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function edit (Video $video)
	{
		return view('admin.tv-series.attributes.edit')->with('payload', $video);
	}

	public function update (UpdateRequest $request, Video $video) : \Illuminate\Http\RedirectResponse
	{
		$video->update($request->validated());
		return response()->redirectTo(
			route('admin.tv-series.edit.action', $video->id)
		)->with('success', 'Attributes updated successfully.');
	}
}