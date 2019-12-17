<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Interfaces\StatusCodes;
use App\Interfaces\Tables;
use App\Models\Slider;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SlidersController extends BaseController{
	use ValidatesRequest;
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.admin.sliders');
	}

	public function index(){
		$slides = Slider::all();
		return view('admin.sliders.index')->with('slides', $slides);
	}

	public function create(){
		return view('admin.sliders.create');
	}

	public function edit($id){
		$slider = Slider::find($id);
		if ($slider != null) {
			return view('admin.sliders.edit')->with('slide', $slider);
		}
		else {
			return responseWeb()->
			route('admin.sliders.index')->
			error('Could not find slide for that key.')->
			send();
		}
	}

	public function store(Request $request){
		$response = null;
		try {
			$this->requestValid($request, $this->rules['store']);
			$poster = Storage::disk('public')->putFile(Directories::Sliders, $request->file('poster'), 'public');
			Slider::create([
				'title' => $request->title,
				'description' => $request->description,
				'poster' => $poster,
				'target' => $request->target,
				'stars' => $request->stars,
				'active' => $request->active,
			]);
			$response = responseWeb()->
			route('admin.sliders.index')->
			success('Slider created successfully.');
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getError())->
			data($request->all());
		}
		catch (Exception $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getMessage())->
			data($request->all());
		}
		finally {
			return $response->send();
		}
	}

	public function show($id){

	}

	public function delete($id){
		$slider = Slider::find($id);
		if ($slider == null) {
			return $this->failed()->
			message('Could not find slide for that key.')->
			status(StatusCodes::ResourceNotFound)->
			send();
		}
		else {
			$slider->delete();
			return $this->success()->
			status(StatusCodes::Okay)->
			message('Successfully deleted slide.')->
			send();
		}
	}

	public function update(Request $request){
		$response = null;
		$poster = null;
		try {
			$this->requestValid($request, $this->rules['update']);
			$slide = Slider::find($request->id);
			$slide->update([
				'title' => $request->title,
				'description' => $request->description,
				'target' => $request->target,
				'stars' => $request->stars,
				'active' => $request->active,
			]);
			if ($request->hasFile('poster')) {
				$poster = Storage::putFile(Directories::Sliders, $request->file('poster'), 'public');
				$slide->setPoster($poster)->save();
			}
			$response = responseWeb()->
			route('admin.sliders.index')->
			success('Slider updated successfully.');
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getError())->
			data($request->all());
		}
		catch (Exception $exception) {
			$response = responseWeb()->
			back()->
			error($exception->getMessage())->
			data($request->all());
		}
		finally {
			return $response->send();
		}
	}

	public function updateStatus(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => ['bail', 'required', Rule::exists(Tables::Sliders, 'id')],
			'active' => ['bail', 'required', 'boolean'],
		]);
		if ($validator->fails()) {
			return $this->failed()->
			message($validator->errors()->first())->
			status(StatusCodes::ResourceNotFound)->
			send();
		}
		else {
			Slider::find($request->id)->
			setActive($request->active)->
			save();
			return $this->success()->
			status(StatusCodes::Okay)->
			message('Successfully updated active status.')->
			send();
		}
	}
}