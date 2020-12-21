<?php

namespace App\Http\Controllers\Web\Admin\Shop;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Uploads;
use App\Models\ShopSlider;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
				'id' => ['bail', 'required', Rule::exists(Tables::ShopSliders, 'id')],
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

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$slider = ShopSlider::findOrFail($id);
			$response = view('admin.shop.sliders.edit')->with('slide', $slider);
		} catch (ModelNotFoundException $exception) {
			$response->error('Could not find shop slider for that key.')->route('admin.shop.sliders.index');
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->route('admin.shop.sliders.index');
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function store ()
	{
		$response = responseWeb();
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
			$payload['banner'] = \request()->hasFile('banner') ? Uploads::access()->putFile(Directories::ShopSliders, request()->file('banner')) : null;
			ShopSlider::create($payload);
			$response->route('admin.shop.sliders.index')->success('Shop slider created successfully.');
		} catch (ValidationException $exception) {
			$response->back()->data(\request()->all())->error($exception->getMessage());
		} catch (Exception $exception) {
			$response->back()->data(\request()->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete ($id)
	{
		$response = responseApp();
		try {
			$slider = ShopSlider::findOrFail($id);
			$slider->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Shop slider deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find shop slider for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
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
			$slide = ShopSlider::findOrFail($id);
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

	public function updateStatus ()
	{
		$response = responseApp();
		try {
			$validated = (object)$this->requestValid(\request(), $this->rules['updateStatus']);
			$slider = ShopSlider::find($validated->id);
			$slider->setActive($validated->active);
			$slider->save();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Status updated successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find shop slider for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}