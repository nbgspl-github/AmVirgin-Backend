<?php

namespace App\Http\Modules\Admin\Controllers\Web\Shop;

use App\Http\Modules\Shared\Controllers\BaseController;
use App\Http\Requests\Sliders\Shop\StatusRequest;
use App\Http\Requests\Sliders\Shop\StoreRequest;
use App\Http\Requests\Sliders\Shop\UpdateRequest;
use App\Library\Enums\Common\PageSectionType;
use App\Models\Slider;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class SliderController extends BaseController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (): Renderable
    {
        $slides = Slider::query()->where('section', PageSectionType::Shop)->latest()->get();
        return view('admin.shop.sliders.index')->with('slides', $slides);
    }

    public function create (): Renderable
    {
        return view('admin.shop.sliders.create');
    }

    public function edit (Slider $slider): Renderable
    {
        return view('admin.shop.sliders.edit')->with('slide', $slider);
    }

    public function store (StoreRequest $request): RedirectResponse
    {
        Slider::query()->create($request->validated());
        return redirect()->route('admin.shop.sliders.index')->with('success', 'Shop slider created successfully.');
    }

    public function update (UpdateRequest $request, Slider $slider): RedirectResponse
    {
        $slider->update($request->validated());
        return redirect()->route('admin.shop.sliders.index')->with('success', 'Shop slider updated successfully.');
    }

    public function updateStatus (StatusRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $slider = Slider::query()->find($validated['id']);
        $slider->update([
            'active' => $validated['active']
        ]);
        return responseApp()->prepare(
            [], Response::HTTP_OK, 'Status updated successfully.'
        );
    }

    /**
     * @param Slider $slider
     * @return JsonResponse
     * @throws Exception
     */
    public function delete (Slider $slider): JsonResponse
    {
        $slider->delete();
        return responseApp()->prepare(
            [], Response::HTTP_OK, 'Slider deleted successfully.'
        );
    }
}