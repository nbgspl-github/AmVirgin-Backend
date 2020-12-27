<?php

namespace App\Http\Modules\Admin\Controllers\Web\Users;

use App\Http\Modules\Admin\Repository\User\Seller\Contracts\SellerRepository;
use App\Http\Modules\Admin\Requests\Users\Seller\StoreRequest;
use App\Http\Modules\Admin\Requests\Users\Seller\UpdateRequest;
use App\Models\Auth\Seller;

class SellerController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected SellerRepository $repository;

	public function __construct (SellerRepository $repository)
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->repository = $repository;
	}

	public function index ()
	{
		return view('admin.sellers.index')->with('users',
			$this->repository->recentPaginated($this->paginationChunk())
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
		$this->repository->create($request->validated());
		return redirect()->route(
			'admin.sellers.index'
		);
	}

	public function update (UpdateRequest $request, Seller $seller) : \Illuminate\Http\RedirectResponse
	{
		$this->repository->update($seller, $request->validated());
		return redirect()->route(
			'admin.sellers.index'
		)->with('success', 'Seller details updated successfully.');
	}

	public function destroy (Seller $seller) : \Illuminate\Http\JsonResponse
	{
		$this->repository->delete($seller);
		return response()->json(
			[]
		);
	}
}