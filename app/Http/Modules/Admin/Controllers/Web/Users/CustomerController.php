<?php

namespace App\Http\Modules\Admin\Controllers\Web\Users;

use App\Http\Modules\Admin\Requests\Users\Customer\StoreRequest;
use App\Http\Modules\Admin\Requests\Users\Customer\UpdateRequest;
use App\Models\Auth\Customer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CustomerController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    /**
     * @var Customer
     */
    protected $model;

    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
        $this->model = app(Customer::class);
    }

    public function index ()
    {
        return view('admin.customers.index')->with('users',
            $this->paginateWithQuery(
                $this->model->newQuery()->latest()->whereLike('name', $this->queryParameter()))
        );
    }

    public function create ()
    {
        return view('admin.customers.create');
    }

    public function edit (Customer $customer)
    {
        return view('admin.customers.edit')->with('customer', $customer);
    }

    public function store (StoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $this->model->newQuery()->create($request->validated());
        return redirect()->route(
            'admin.customers.index'
        );
    }

    public function show (Customer $customer): JsonResponse
    {
        return response()->json(
            view('admin.customers.show')->with('customer', $customer)->render()
        );
    }

    public function update (UpdateRequest $request, Customer $customer): \Illuminate\Http\RedirectResponse
    {
        $customer->update($request->validated());
        return redirect()->route('admin.customers.index')->with('success', 'Customer details updated successfully.');
    }

    /**
     * @param Customer $customer
     * @return JsonResponse
     * @throws Exception
     */
    public function delete (Customer $customer): JsonResponse
    {
        $customer->delete();
        return responseApp()->prepare(
            [], Response::HTTP_OK, 'Customer deleted successfully.'
        );
    }
}