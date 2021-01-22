<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Http\Modules\Customer\Requests\Brand\UpdateRequest;
use App\Models\Brand;

class BrandController extends WebController
{
	/**
	 * @var Brand
	 */
	protected $model;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->model = app(Brand::class);
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