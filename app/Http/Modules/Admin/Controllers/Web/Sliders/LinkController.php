<?php

namespace App\Http\Modules\Admin\Controllers\Web\Sliders;

use App\Http\Modules\Admin\Requests\Slider\Link\StoreRequest;
use App\Http\Modules\Admin\Requests\Slider\Link\UpdateRequest;
use App\Models\Slider;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class LinkController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function create (): Renderable
    {
        return view('admin.sliders.link.create');
    }

    public function edit (Slider $slider): Renderable
    {
        return view('admin.sliders.link.edit')->with('slider', $slider);
    }

    public function store (StoreRequest $request): RedirectResponse
    {
        Slider::query()->create($request->validated());
        return redirect()->route('admin.sliders.index')->with('success', 'Link slider created successfully.');
    }

    public function update (UpdateRequest $request, Slider $slider): RedirectResponse
    {
        $slider->update($request->validated());
        return redirect()->route('admin.sliders.index')->with('success', 'Link slider updated successfully.');
    }
}