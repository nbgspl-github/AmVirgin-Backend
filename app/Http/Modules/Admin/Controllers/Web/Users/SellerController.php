<?php

namespace App\Http\Modules\Admin\Controllers\Web\Users;

use App\Http\Modules\Admin\Requests\Users\Seller\StoreRequest;
use App\Http\Modules\Admin\Requests\Users\Seller\UpdateRequest;
use App\Models\Auth\Seller;

class SellerController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	/**
	 * @var Seller
	 */
	protected $model;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->model = app(Seller::class);
	}

	public function index ()
	{
		return view('admin.sellers.index')->with('users',
			$this->model->newQuery()->paginate($this->paginationChunk())
		);
	}

	public function create ()
	{
		return view('admin.sellers.create');
	}

	public function edit (Seller $seller)
	{
		return view('admin.sellers.edit')->with('seller', $seller);
	}


	public function show (Seller $seller) : \Illuminate\Http\JsonResponse
	{
		return response()->json(
			view('admin.sellers.show')->with('seller', $seller)->render()
		);
	}

	public function store (StoreRequest $request) : \Illuminate\Http\RedirectResponse
	{
		$this->model->create($request->validated());
		return redirect()->route(
			'admin.sellers.index'
		);
	}

	public function update (UpdateRequest $request, Seller $seller) : \Illuminate\Http\RedirectResponse
	{
		$seller->update($request->validated());
		return redirect()->route(
			'admin.sellers.index'
		)->with('success', 'Seller details updated successfully.');
	}

	/**
	 * @param Seller $seller
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function destroy (Seller $seller) : \Illuminate\Http\JsonResponse
	{
		$seller->delete();
		return response()->json(
			[]
		);
	}
}