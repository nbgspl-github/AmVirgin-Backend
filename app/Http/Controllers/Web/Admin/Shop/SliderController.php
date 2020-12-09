<?php

namespace App\Http\Controllers\Web\Admin\Shop;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\ShopSlider;
use App\Models\Slider;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
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
			$slider = ShopSlider::retrieveThrows($id);
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
			$payload['banner'] = \request()->hasFile('banner') ? SecuredDisk::access()->putFile(Directories::ShopSliders, request()->file('banner')) : null;
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
			$slider = ShopSlider::retrieveThrows($id);
			$slider->delete();
			$response->status(HttpOkay)->message('Shop slider deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find shop slider for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
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
			$slide = ShopSlider::retrieveThrows($id);
			$slide->update([
				'title' => $validated->title,
				'description' => $validated->description,
				'target' => $validated->target,
				'rating' => $validated->rating,
				'active' => $validated->active,
			]);
			if (request()->hasFile('banner')) {
				SecuredDisk::deleteIfExists($slide->banner);
				$banner = SecuredDisk::access()->putFile(Directories::Sliders, request()->file('banner'));
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
			$slider = ShopSlider::retrieve($validated->id);
			$slider->setActive($validated->active);
			$slider->save();
			$response->status(HttpOkay)->message('Status updated successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find shop slider for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}