<?php

namespace App\Http\Modules\Admin\Controllers\Web\Shop;

use App\Http\Modules\Shared\Controllers\BaseController;
use App\Models\ShopSlider;
use App\Models\Slider;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Throwable;

class SliderController extends BaseController
{
    use ValidatesRequest;

    protected array $rules = [];

    public function __construct ()
    {
        parent::__construct();
        $this->rules = [
            'store' => [
                'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
                'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
                'banner' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp'],
                'target' => ['bail', 'required', 'url'],
                'rating' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
                'active' => ['bail', 'required', 'boolean'],
            ],
            'update' => [
                'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
                'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
                'banner' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
                'target' => ['bail', 'required', 'url'],
                'rating' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
                'active' => ['bail', 'required', 'boolean'],
            ],
            'updateStatus' => [
                'id' => ['bail', 'required', ShopSlider::exists()],
                'active' => ['bail', 'required', 'boolean'],
            ],
        ];
    }

    public function index (): Renderable
    {
        $slides = ShopSlider::all();
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

    public function store (): RedirectResponse
    {
        $payload = $this->requestValid(request(), $this->rules['store']);
        ShopSlider::query()->create($payload);
        return redirect()->route('admin.shop.sliders.index')->with('success', 'Shop slider created successfully.');
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

    public function update (Slider $slider): RedirectResponse
    {
        $validated = $this->requestValid(request(), $this->rules['update']);
        $slider->update($validated);
        return redirect()->route('admin.shop.sliders.index')->with('success', 'Shop slider updated successfully.');
    }

    public function updateStatus (): JsonResponse
    {
        $response = responseApp();
        try {
            $validated = (object)$this->requestValid(\request(), $this->rules['updateStatus']);
            $slider = ShopSlider::query()->find($validated->id);
            $slider->update([
                'active' => $validated->active
            ]);
            $response->status(Response::HTTP_OK)->message('Status updated successfully.');
        } catch (ModelNotFoundException $exception) {
            $response->status(Response::HTTP_NOT_FOUND)->message('Could not find shop slider for that key.');
        } catch (Throwable $exception) {
            $response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }
}