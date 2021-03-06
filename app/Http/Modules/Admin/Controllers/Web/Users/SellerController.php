<?php

namespace App\Http\Modules\Admin\Controllers\Web\Users;

use App\Http\Modules\Admin\Requests\Users\Seller\StoreRequest;
use App\Http\Modules\Admin\Requests\Users\Seller\UpdateRequest;
use App\Models\Auth\Seller;
use Exception;
use Illuminate\Http\JsonResponse;

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
            $this->paginateWithQuery(
                $this->model->newQuery()->latest()->whereLike('name', $this->queryParameter()))
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

    public function show (Seller $seller): JsonResponse
    {
        return response()->json(
            view('admin.sellers.show')->with('seller', $seller)->render()
        );
    }

    public function store (StoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->model->create($request->validated());
        return redirect()->route(
            'admin.sellers.index'
        );
    }

    public function update (UpdateRequest $request, Seller $seller): \Illuminate\Http\RedirectResponse
    {
        $seller->update($request->validated());
        return redirect()->route(
            'admin.sellers.index'
        )->with('success', 'Seller details updated successfully.');
    }

    /**
     * @param Seller $seller
     * @return JsonResponse
     * @throws Exception
     */
    public function delete (Seller $seller): JsonResponse
    {
        $seller->delete();
        return responseApp()->prepare(
            null, \Illuminate\Http\Response::HTTP_OK, 'Seller deleted successfully.'
        );
    }
}