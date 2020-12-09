<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Interfaces\Tables;
use App\Models\Genre;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GenresController extends BaseController
{
	use ValidatesRequest;

	protected $ruleSet;

	public function __construct ()
	{
		$this->ruleSet = config('rules.admin.genres');
	}

	public function index ()
	{
		$genres = Genre::all();
		return view('admin.genres.index')->with('genres', $genres);
	}

	public function create ()
	{
		return view('admin.genres.create');
	}

	public function edit ($id)
	{
		$genre = Genre::find($id);
		if ($genre != null) {
			return view('admin.genres.edit')->with('genre', $genre);
		} else {
			return responseWeb()->
			route('admin.genres.index')->
			error(__('strings.genre.not-found'))->
			send();
		}
	}

	public function store (Request $request)
	{
		$response = responseWeb();
		try {
			$payload = $this->requestValid($request, $this->ruleSet['store']);
			$payload['poster'] = null;
			if ($request->hasFile('poster'))
				$payload = Storage::disk('secured')->putFile(Directories::Genre, $request->file('poster'), 'public');

			Genre::create($payload);
			$response->success(__('strings.genre.store.success'))->route('admin.genres.index');
		} catch (ValidationException $exception) {
			$response->error($exception->getError())->back()->data($request->all());
		} catch (Exception $exception) {
			$response->error($exception->getMessage())->back()->data($request->all());
		} finally {
			return $response->send();
		}
	}

	public function update (Request $request, $id)
	{
		$response = responseWeb();
		$genre = Genre::retrieve($id);
		try {
			if ($genre == null)
				throw new ModelNotFoundException(__('strings.genre.not-found'));

			$additional = [
				'name' => [Rule::unique(Tables::Genres, 'name')->ignoreModel($genre)],
			];
			$payload = $this->requestValid($request, $this->ruleSet['update'], $additional);
			if ($request->hasFile('poster'))
				$payload['poster'] = Storage::disk('secured')->putFile(Directories::Genre, $request->file('poster'), 'public');
			else
				unset($payload['poster']);
			$genre->update($payload);
			$response->success(__('strings.genre.store.success'))->route('admin.genres.index');
		} catch (ModelNotFoundException $exception) {
			$response->error($exception->getMessage())->route('admin.genres.index');
		} catch (ValidationException $exception) {
			$response->error($exception->getError())->back()->data($request->all());
		} catch (Exception $exception) {
			$response->error($exception->getMessage())->back()->data($request->all());
		} finally {
			return $response->send();
		}
	}

	public function updateStatus (Request $request)
	{
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

	public function delete ($id = null)
	{
		$validator = Validator::make(['id' => $id], [
			'id' => ['bail', 'required', Rule::exists(Tables::Genres, 'id')],
		]);
		if ($validator->fails()) {
			return response()->json(['code' => 400, 'message' => 'Could not find genre for that key.']);
		} else {
			Genre::find($id)->delete();
			return response()->json(['code' => 200, 'message' => 'Successfully deleted genre.']);
		}
	}
}