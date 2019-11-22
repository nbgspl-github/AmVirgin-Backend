<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;
use App\Http\Resources\MovieResource;
use App\Http\Resources\MoviesCollection;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GenresController extends WebController {
	public function index($id = null) {
		if ($id == null) {
			$genres = Genre::all();
			return view('genres.all')->with('genres', $genres);
		} else {
			$genre = Genre::find($id);
			return $genre != null ? (view('genres.add')) : null;
		}
	}

	public function create() {
		return view('genres.add');
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:100'],
			'poster' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
			'status' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			notify()->error($validator->errors()->first());
			return back();
		} else {
			$poster = null;
			if ($request->hasFile('poster'))
				$poster = Storage::putFile(Directories::Genre, $request->file('poster'), 'public');

			Genre::makeNew()->
			setName($request->name)->
			setDescription($request->description)->
			setPoster($poster)->
			setStatus($request->status)->
			save();
			notify()->success('Genre added successfully.');
			return back();
		}
	}

	public function updateStatus(Request $request) {
		$validator = Validator::make($request->all(), [
			'id' => ['bail', 'required', Rule::exists(Tables::Genres, 'id')],
			'status' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			return response()->json(['message' => $validator->errors()->first()], 400);
		} else {
			return response()->json(['message' => 'Status updated successfully.'], 200);
		}
	}
}