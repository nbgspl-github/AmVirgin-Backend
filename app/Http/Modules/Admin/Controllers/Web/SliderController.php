<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Rule;
use App\Library\Utils\Uploads;
use App\Models\Slider;
use App\Models\Video\Video;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderController extends BaseController
{
	use ValidatesRequest;
	use FluentResponse;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
				'banner' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp'],
				'type' => ['bail', 'required', Rule::in([Slider::TargetType['ExternalLink'], Slider::TargetType['VideoKey']])],
				'targetLink' => ['bail', 'required_if:type,external-link', 'url'],
				'targetKey' => ['bail', 'required_if:type,video-key', Rule::existsPrimary(Tables::Videos)->where('pending', false)],
				'rating' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
				'active' => ['bail', 'required', 'boolean'],
			],
			'update' => [
				'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
				'banner' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
				'type' => ['bail', 'required', Rule::in([Slider::TargetType['ExternalLink'], Slider::TargetType['VideoKey']])],
				'targetLink' => ['bail', 'required_if:type,external-link', 'url'],
				'targetKey' => ['bail', 'required_if:type,video-key', Rule::existsPrimary(Tables::Videos)->where('pending', false)],
				'rating' => ['bail', 'required', 'numeric', 'min:0', 'max:5'],
				'active' => ['bail', 'required', 'boolean'],
			],
		];
	}

	public function index ()
	{
		$slides = Slider::all();
		return view('admin.sliders.index')->with('slides', $slides);
	}

	public function create ()
	{
		$videos = Video::where('pending', false)->get(['id', 'title']);
		return view('admin.sliders.create')->with('videos', $videos);
	}

	public function edit ($id)
	{
		$videos = Video::where('pending', false)->get(['id', 'title']);
		$slider = Slider::find($id);
		if ($slider != null) {
			return view('admin.sliders.edit')->with('slide', $slider)->with('videos', $videos);
		} else {
			return responseWeb()->
			route('admin.sliders.index')->
			error('Could not find slide for that key.')->
			send();
		}
	}

	public function store (Request $request)
	{
		$response = responseWeb();
		try {
			$validated = $this->requestValid($request, $this->rules['store']);
			$validated['target'] = $validated['type'] == Slider::TargetType['ExternalLink'] ? $validated['targetLink'] : $validated['targetKey'];
			$validated['banner'] = request()->hasFile('banner') ? Uploads::access()->putFile(Directories::Sliders, $request->file('banner')) : null;
			Slider::create($validated);
			$response->route('admin.sliders.index')->success('Slider created successfully.');
		} catch (ValidationException $exception) {
			$response->back()->error($exception->getError())->data($request->all());
		} catch (\Throwable $exception) {
			$response->back()->error($exception->getMessage())->data($request->all());
		} finally {
			return $response->send();
		}
	}

	public function delete ($id)
	{
		$slider = Slider::find($id);
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
		$response = responseWeb();
		$banner = null;
		try {
			$validated = $this->requestValid($request, $this->rules['update']);
			$slide = Slider::find($request->id);
			$slide->update([
				'title' => $validated['title'],
				'description' => $validated['description'],
				'type' => $validated['type'],
				'target' => $validated['type'] == Slider::TargetType['ExternalLink'] ? $validated['targetLink'] : $validated['targetKey'],
				'rating' => $validated['rating'],
				'active' => $validated['active'],
			]);
			if ($request->hasFile('banner')) {
				$banner = Uploads::access()->putFile(Directories::Sliders, $request->file('banner'));
				$slide->update(['banner' => $banner]);
			}
			$response->route('admin.sliders.index')->success('Slider updated successfully.');
		} catch (ValidationException $exception) {
			$response->back()->error($exception->getError())->data($request->all());
		} catch (\Throwable $exception) {
			$response->back()->error($exception->getMessage())->data($request->all());
		} finally {
			return $response->send();
		}
	}

	public function updateStatus (Request $request)
	{
		$validator = Validator::make($request->all(), [
			'id' => ['bail', 'required', Rule::exists(Tables::Sliders, 'id')],
			'active' => ['bail', 'required', 'boolean'],
		]);
		if ($validator->fails()) {
			return $this->failed()->
			message($validator->errors()->first())->
			status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->
			send();
		} else {
			Slider::find($request->id)->
			setActive($request->active)->
			save();
			return $this->success()->
			status(\Illuminate\Http\Response::HTTP_OK)->
			message('Successfully updated active status.')->
			send();
		}
	}
}