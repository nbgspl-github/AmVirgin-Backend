<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Common\Tables;
use App\Library\Http\Response\WebResponse;
use App\Library\Utils\Extensions\Rule;
use App\Library\Utils\Uploads;
use App\Models\Brand;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class BrandController extends BaseController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255', Rule::unique(Tables::Brands, 'name')],
				'logo' => ['bail', 'required', 'image', 'min:1', 'max:5120'],
			],
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'logo' => ['bail', 'nullable', 'image', 'min:1', 'max:5120'],
			],
		];
	}

	public function index ()
	{
		$brands = Brand::all();
		return view('admin.brands.index')->with('brands', $brands);
	}

	public function create ()
	{
		return view('admin.brands.create');
	}

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$brand = Brand::findOrFail($id);
			$response = view('admin.brands.edit')->with('payload', $brand);
		} catch (ModelNotFoundException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function approve ($id)
	{
		$response = responseWeb();
		try {
			$brand = Brand::findOrFail($id);
			/**
			 * @var Category $category
			 */
			$category = $brand->category;
			$categories = $category->descendants(false);
			$categories->each(function (Category $category) use ($brand) {
				$cloned = $brand->replicate();
				$cloned->categoryId = $category->getKey();
				$cloned->status = 'approved';
				$cloned->active = 1;
				$cloned->save();
			});
			$brand->status = 'approved';
			$brand->active = 1;
			$brand->save();
			$response->back()->success('Brand approved successfully for related categories.');
		} catch (ModelNotFoundException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
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
			$validated = $this->requestValid(request(), $this->rules['store']);
			$validated['logo'] = Uploads::access()->putFile(Directories::Brands, request()->file('logo'));
			$validated['active'] = request()->has('active');
			Brand::query()->create($validated);
			$response->success('Added brand successfully.')->route('admin.brands.index');
		} catch (ValidationException $e) {
			$response->error($e->getMessage())->data(request()->all())->back();
		} catch (Throwable $e) {
			$response->error($e->getMessage())->data(request()->all())->back();
		} finally {
			return $response->send();
		}
	}

	public function update ($id)
	{
		$response = responseWeb();
		try {
			$validated = $this->requestValid(request(), $this->rules['update']);
			$brand = Brand::query()->where([
				['name', $validated['name']],
				['id', '!=', $id],
			])->first();
			if (!empty($brand)) throw new \Exception('Your given brand name is already taken. Try again with a different one.');
			$brand = Brand::findOrFail($id);
			if (request()->hasFile('logo')) $validated['logo'] = Uploads::access()->putFile(Directories::Brands, request()->file('logo'));
			$validated['active'] = request()->has('active');
			$brand->update($validated);
			$response->success('Updated brand details successfully.')->route('admin.brands.index');
		} catch (ModelNotFoundException $e) {
			$response->error($e->getMessage())->data(request()->all())->route('admin.brands.index');
		} catch (ValidationException $e) {
			$response->error($e->getMessage())->data(request()->all())->back();
		} catch (Throwable $e) {
			$response->error($e->getMessage())->data(request()->all())->back();
		} finally {
			return $response->send();
		}
	}
}