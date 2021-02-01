<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

class MediaController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit (\App\Models\Video\Video $video) : \Illuminate\Contracts\Support\Renderable
	{
		return view('admin.tv-series.media.edit')->with('video', $video);
	}

	public function update (\App\Http\Modules\Admin\Requests\TvSeries\Media\UpdateRequest $request, \App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		$video->update($request->validated());
		return responseApp()->prepare(
			['route' => route('admin.tv-series.edit.action', $video->id)], \Illuminate\Http\Response::HTTP_OK, 'Tv series media updated successfully.'
		);
	}
}