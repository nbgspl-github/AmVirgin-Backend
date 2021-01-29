<?php

namespace App\Http\Modules\Admin\Controllers\Web\Payments;

class PaymentController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : \Illuminate\Contracts\Support\Renderable
	{
		$payments = \App\Models\SellerPayment::query()->latest();
		return view('admin.payments.index')->with('payments',
			$this->paginateWithQuery($payments->whereHas('order', function (\Illuminate\Database\Eloquent\Builder $builder) {
				$builder->whereLike('number', $this->queryParameter());
			}))
		);
	}

	public function show (\App\Models\SellerPayment $payment)
	{

	}
}