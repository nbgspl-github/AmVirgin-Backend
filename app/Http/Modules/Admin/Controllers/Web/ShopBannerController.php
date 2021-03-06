<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Common\Tables;
use App\Models\ShopSlider;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ShopBannerController extends BaseController
{
	use ValidatesRequest;
	use FluentResponse;

	protected $ruleSet;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet = config('rules.admin.home-banner');
	}

	public function index ()
	{
		$slides = ShopSlider::all();
		return view('admin.home-banners.index')->with('slides', $slides);
	}

	public function create ()
	{
		return view('admin.home-banners.create');
	}

	public function edit ($id)
	{
		$slider = ShopSlider::find($id);
		if ($slider != null) {
			return view('admin.home-banners.edit')->with('slide', $slider);
		} else {
			return responseWeb()->
			route('admin.shop-banners.index')->
			error('Could not find slide for that key.')->
			send();
		}
	}

	public function store (Request $request)
	{
		$response = null;

		try {

			$payload = $this->requestValid($request, $this->ruleSet['store']);
			$payload['banner'] = \request()->hasFile('banner') ? Storage::disk('secured')->putFile(Directories::ShopBanners, $request->file('banner'), 'public') : null;
			ShopSlider::create($payload);
			$response = responseWeb()->
			route('admin.shop-banners.index')->
			success('Home Banner created successfully.');
		} catch (ValidationException $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getError())->
			data($request->all());
		} catch (Exception $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getMessage())->
			data($request->all());
		} finally {
			return $response->send();
		}
	}

	public function show ($id)
	{

	}

	public function delete ($id)
	{
		$slider = ShopSlider::find($id);
		if ($slider == null) {
			return $this->failed()->
			message('Could not find slide for that key.')->
			status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->
			send();
		} else {
			$slider->delete();
			return $this->success()->
			status(\Illuminate\Http\Response::HTTP_OK)->
			message('Successfully deleted slide.')->
			send();
		}
	}

	public function update (Request $request)
	{
		$response = null;
		$poster = null;
		try {
			$this->requestValid($request, $this->ruleSet['update']);
			$slide = ShopSlider::find($request->id);
			$slide->update([
				'title' => $request->title,
				'description' => $request->description,
				'target' => $request->target,
				'stars' => $request->stars,
				'active' => $request->active,
			]);
			if ($request->hasFile('banner')) {
				$poster = Storage::disk('secured')->putFile(Directories::ShopBanners, $request->file('banner'), 'public');
				$slide->setBanner($poster)->save();
			}
			$response = responseWeb()->
			route('admin.shop-banners.index')->
			success('Slider updated successfully.');
		} catch (ValidationException $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getError())->
			data($request->all());
		} catch (Exception $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getMessage())->
			data($request->all());
		} finally {
			return $response->send();
		}
	}

	public function updateStatus (Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => ['bail', 'required', Rule::exists(Tables::ShopBanner, 'id')],
			'active' => ['bail', 'required', 'boolean'],
		]);
		if ($validator->fails()) {
			return $this->failed()->
			message($validator->errors()->first())->
			status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->
			send();
		} else {
			ShopSlider::find($request->id)->
			setActive($request->active)->
			save();
			return $this->success()->
			status(\Illuminate\Http\Response::HTTP_OK)->
			message('Successfully updated active status.')->
			send();
		}
	}
}