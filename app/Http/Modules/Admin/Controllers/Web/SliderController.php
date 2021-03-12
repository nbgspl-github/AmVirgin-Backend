<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Http\Modules\Admin\Requests\Slider\StatusRequest;
use App\Models\Slider;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class SliderController extends WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function index (): Renderable
    {
        return view('admin.sliders.index')->with('slides',
            Slider::query()->latest()->get()
        );
    }

    public function edit (Slider $slider): RedirectResponse
    {
        if ($slider->type == 'external-link') {
            return redirect()->route('admin.sliders.link.edit', $slider->id);
        }
        return redirect()->route('admin.sliders.video.edit', $slider->id);
    }

    public function status (StatusRequest $request, Slider $slider): JsonResponse
    {
        $slider->update($request->validated());
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Status updated successfully.'
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
            [], \Illuminate\Http\Response::HTTP_OK, 'Slider deleted successfully.'
        );
    }
}