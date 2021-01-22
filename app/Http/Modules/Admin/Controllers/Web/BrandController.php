<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Http\Modules\Customer\Requests\Brand\UpdateRequest;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Rule;
use App\Models\Brand;
use App\Traits\ValidatesRequest;

class BrandController extends BaseController
{
	use ValidatesRequest;

	protected array $rules;

	/**
	 * @var Brand
	 */
	protected $model;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->model = app(Brand::class);
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
		return view('admin.brands.index')->with('brands',
			$this->paginateWithQuery(
				$this->model->newQuery()->latest()->whereLike('name', $this->queryParameter()))
		);
	}

	public function create ()
	{
		return view('admin.brands.create');
	}

	public function show (Brand $brand)
	{
		return view('admin.brands.show')->with('brand', $brand);
	}

	public function update (UpdateRequest $request, Brand $brand) : \Illuminate\Http\RedirectResponse
	{
		$brand->update($request->validated());
		return redirect()->route('admin.brands.index')->with('success', 'Brand details updated successfully.');
	}

	/**
	 * @param Brand $brand
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function delete (Brand $brand) : \Illuminate\Http\JsonResponse
	{
		$brand->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Brand deleted successfully.'
		);
	}
}