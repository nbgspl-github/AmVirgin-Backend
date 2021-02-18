<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Http\Modules\Admin\Requests\Slider\StatusRequest;
use App\Http\Modules\Admin\Requests\Slider\StoreRequest;
use App\Http\Modules\Admin\Requests\Slider\UpdateRequest;
use App\Models\Slider;
use App\Models\Video\Video;

class SliderController extends WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$slides = Slider::all();
		return view('admin.sliders.index')->with('slides', $slides);
	}

	public function create ()
	{
		return view('admin.sliders.create')->with('videos', $this->videos());
	}

	public function edit (Slider $slider)
	{
		return view('admin.sliders.edit')->with('slide', $slider)->with('videos', $this->videos());
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		$validated = $request->validated();
		$validated['target'] = $validated['type'] == Slider::TargetType['ExternalLink'] ? $validated['targetLink'] : $validated['targetKey'];
		Slider::query()->create($validated);
		return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully.');
	}

	public function update (UpdateRequest $request, Slider $slider) : \Illuminate\Http\RedirectResponse
	{
		$slider->update($request->validated());
		return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully.');
	}

	public function status (StatusRequest $request, Slider $slider) : \Illuminate\Http\JsonResponse
	{
		$slider->update($request->validated());
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Status updated successfully.'
		);
	}

	/**
	 * @param Slider $slider
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (Slider $slider) : \Illuminate\Http\JsonResponse
	{
		$slider->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Slider deleted successfully.'
		);
	}

	protected function videos ()
	{
		return Video::query()->where('pending', false)->get(['id', 'title']);
	}
}