<?php

namespace App\Http\Modules\Admin\Controllers\Web\Shop;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Uploads;
use App\Models\ShopSlider;
use App\Models\Slider;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
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

    public function index ()
    {
        $slides = ShopSlider::all();
        return view('admin.shop.sliders.index')->with('slides', $slides);
    }

    public function create ()
    {
        return view('admin.shop.sliders.create');
    }

    public function edit (Slider $slider)
    {
        return view('admin.shop.sliders.edit')->with('slide', $slider);
    }

    public function store ()
    {
        $response = responseWeb();
        $payload = $this->requestValid(request(), $this->rules['store']);
        $payload['banner'] = \request()->hasFile('banner') ? Uploads::access()->putFile(Directories::ShopSliders, request()->file('banner')) : null;
        ShopSlider::query()->create($payload);
        $response->route('admin.shop.sliders.index')->success('Shop slider created successfully.');
        return $response->send();
    }

    public function delete ($id): JsonResponse
    {
        $response = responseApp();
        try {
            $slider = ShopSlider::query()->findOrFail($id);
            $slider->delete();
            $response->status(Response::HTTP_OK)->message('Shop slider deleted successfully.');
        } catch (ModelNotFoundException $exception) {
            $response->status(Response::HTTP_NOT_FOUND)->message('Could not find shop slider for that key.');
        } catch (Throwable $exception) {
            $response->status(Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    public function update ($id)
    {
        $response = responseWeb();
        $banner = null;
        try {
            $validated = (object)$this->requestValid(request(), $this->rules['update']);
            $slide = ShopSlider::query()->findOrFail($id);
            $slide->update([
                'title' => $validated->title,
                'description' => $validated->description,
                'target' => $validated->target,
                'rating' => $validated->rating,
                'active' => $validated->active,
            ]);
            if (request()->hasFile('banner')) {
                Uploads::deleteIfExists($slide->banner);
                $banner = Uploads::access()->putFile(Directories::Sliders, request()->file('banner'));
                $slide->update([
                    'banner' => $banner,
                ]);
            }
            $response->route('admin.shop.sliders.index')->success('Shop slider updated successfully.');
        } catch (ModelNotFoundException $exception) {
            $response->route('admin.shop.sliders.index')->error($exception->getMessage());
        } catch (ValidationException $exception) {
            $response->back()->error($exception->getMessage())->data(\request()->all());
        } catch (Throwable $exception) {
            $response->back()->error($exception->getMessage())->data(\request()->all());
        } finally {
            return $response->send();
        }
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