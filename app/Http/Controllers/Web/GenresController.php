<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;
use App\Http\Resources\MovieResource;
use App\Http\Resources\MoviesCollection;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\Genre;
use App\Rules\UniqueExceptSelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GenresController extends WebController {
	public function index() {
		$genres = Genre::all();
		return view('genres.all')->with('genres', $genres);
	}

	public function create() {
		return view('genres.add');
	}

	public function edit($id) {
		$genre = Genre::find($id);
		if ($genre != null) {
			return view('genres.edit')->with('genre', $genre);
		} else {
			notify()->error('Could not find any genre with that Id.');
			return redirect(route('genres.index'));
		}
	}

	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => ['bail', 'required', 'string', 'min:1', 'max:100', Rule::unique(Tables::Genres, 'name')],
			'poster' => ['bail', 'nullable', 'image'],
			'status' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			notify()->error($validator->errors()->first());
			return back()->withInput($request->all());
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
			return redirect(route('genres.index'));
		}
	}

	public function update(Request $request, $id) {
		$genre = Genre::find($id);
		if ($genre == null) {
			notify()->error('Unable to find genre for that Id.');
			return redirect(route('genres.index'));
		} else {
			$validator = Validator::make($request->all(), [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:100', new UniqueExceptSelf(Genre::class, 'name', $request->name, $request->id)],
				'poster' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
				'status' => ['bail', 'required', Rule::in([0, 1])],
			]);
			if ($validator->fails()) {
				notify()->error($validator->errors()->first());
				return back()->withInput($request->all());
			} else {
				$poster = null;
				if ($request->hasFile('poster'))
					$poster = Storage::putFile(Directories::Genre, $request->file('poster'), 'public');

				$genre->
				setName($request->name)->
				setDescription($request->description)->
				setStatus($request->status)->
				save();

				if ($poster != null) {
					$genre->
					setPoster($poster)->
					save();
				}

				notify()->success('Updated genre details successfully.');
				return redirect(route('genres.index'));
			}
		}
	}

	public function updateStatus(Request $request) {
		$validator = Validator::make($request->all(), [
			'id' => ['bail', 'required', Rule::exists(Tables::Genres, 'id')],
			'status' => ['bail', 'required', Rule::in([0, 1])],
		]);
		if ($validator->fails()) {
			return response()->json(['code' => 400, 'message' => $validator->errors()->first()]);
		} else {
			Genre::find($request->id)->
			setStatus($request->status)->
			save();
			return response()->json(['code' => 200, 'message' => 'Status updated successfully.']);
		}
	}

	public function delete($id = null) {
		$validator = Validator::make(['id' => $id], [
			'id' => ['bail', 'required', Rule::exists(Tables::Genres, 'id')],
		]);
		if ($validator->fails()) {
			return response()->json(['code' => 400, 'message' => sprintf("Got id = %d", $id)]);
		} else {
			Genre::find($id)->delete();
			return response()->json(['code' => 200, 'message' => 'Successfully deleted genre.']);
		}
	}
}