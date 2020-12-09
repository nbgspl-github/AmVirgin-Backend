<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Models\CategoryBanner;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CategoriesBanner extends BaseController
{
	use ValidatesRequest;
	use FluentResponse;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.categories-banner');
	}

	public function index ()
	{
		$categories = CategoryBanner::all();

		return view('admin.categories-banner.index')->with('categoriesBanner', $categories);
	}

	public function create ()
	{

		return view('admin.categories-banner.create');
	}

	public function store (Request $request)
	{
		$images = [];
		$response = null;
		try {
			$payload = $this->requestValid($request, $this->rules('store'));
			$images = [];
			if (count($request->file('image')) > 0) {
				foreach ($request->file('image') as $imgdata) {

					$images[] = Storage::disk('secured')->putFile(Directories::CategoriesBanner, $imgdata, 'private');

				}
			}

			$payload['image'] = implode(",", $images);
			CategoryBanner::create($payload);
			$response = responseWeb()->route('admin.categories-banner.index')->success('Created category banner successfully.');
		} catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getError());
		} catch (Throwable $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function edit ($id = null)
	{
		$response = null;
		$categoriesdata = CategoryBanner::where('id', $id)->first();
		if ($categoriesdata != null) {
			return view('admin.categories-banner.edit')->with('categoriesBanner', $categoriesdata);
		} else {
			return responseWeb()->route('admin.categories-banner.index')->error('Could not find a category banner with that key.')->send();
		}
	}

	public function update (Request $request, $id = null)
	{

		$response = null;
		try {
			$category = CategoryBanner::retrieveThrows($id);
			$images = [];
			//update Directory new image
			if (count($request->file('image')) > 0) {
				$oldimage = CategoryBanner::where('id', $id)->select('image')->first();
				//delete Direcetory old image
				if (!empty($oldimage->image)) {
					$oldImagesArr = explode(",", $oldimage->image);
					$image_path = [];
					for ($i = 0; $i < count($oldImagesArr); $i++) {
						$image_path = $oldImagesArr[$i];
						Storage::disk('secured')->delete($image_path);
					}
				}
				//Upload New imag
				$images = [];
				if (count($request->file('image')) > 0) {
					foreach ($request->file('image') as $imgdata) {
						$images[] = Storage::disk('secured')->putFile(Directories::CategoriesBanner, $imgdata, 'private');
					}
				}

				$payloadimg['image'] = implode(",", $images);
				$category->update($payloadimg);
			}

			$payload = $this->requestValid($request, $this->rules('update'));
			$category->update($payload);
			$response = responseWeb()->route('admin.categories-banner.index')->success('Update category banner successfully.');
		} catch (ModelNotFoundException $exception) {
			$response = responseWeb()->route('admin.categories.index')->error('Could not find category for that key.');
		} catch (ValidationException $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getError());
		} catch (Throwable $exception) {
			$response = responseWeb()->back()->data($request->all())->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function delete ($id)
	{
		$categories = CategoryBanner::whereid($id)->first();
		try {

			$category = CategoryBanner::retrieveThrows($id);
			if (!empty($categories->image)) {
				$images = explode(",", $categories->image);
				$image_path = [];
				for ($i = 0; $i < count($images); $i++) {
					$image_path = $images[$i];
					Storage::disk('secured')->delete($image_path);
				}

			}
			$category->delete();

			return $this->success()->message('Category deleted successfully.')->status(HttpOkay)->send();
		} catch (ModelNotFoundException $exception) {
			return $this->failed()->message($exception->getMessage())->status(HttpResourceNotFound)->send();
		}
	}
}