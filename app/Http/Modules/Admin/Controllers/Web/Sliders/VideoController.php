<?php

namespace App\Http\Modules\Admin\Controllers\Web\Sliders;

use App\Http\Modules\Admin\Requests\Slider\Video\StoreRequest;
use App\Http\Modules\Admin\Requests\Slider\Video\UpdateRequest;
use App\Models\Slider;
use App\Models\Video\Video;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;

class VideoController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function create (): Renderable
    {
        return view('admin.sliders.video.create')->with('videos', $this->videos());
    }

    public function edit (Slider $slider): Renderable
    {
        return view('admin.sliders.video.edit')->with('slider', $slider)->with('videos', $this->videos());
    }

    public function store (StoreRequest $request): RedirectResponse
    {
        Slider::query()->create($request->validated());
        return redirect()->route('admin.sliders.index')->with('success', 'Video slider created successfully.');
    }

    public function update (UpdateRequest $request, Slider $slider): RedirectResponse
    {
        $slider->update($request->validated());
        return redirect()->route('admin.sliders.index')->with('success', 'Video slider updated successfully.');
    }

    protected function videos (): Collection|array
    {
        return Video::query()->where('pending', false)->get(['id', 'title']);
    }
}