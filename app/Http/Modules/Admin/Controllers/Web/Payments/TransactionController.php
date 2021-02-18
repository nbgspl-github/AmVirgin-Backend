<?php

namespace App\Http\Modules\Admin\Controllers\Web\Payments;

class TransactionController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : \Illuminate\Contracts\Support\Renderable
	{
		$transactions = \App\Models\Models\SellerTransaction::query()->latest();
		return view('admin.transactions.index')->with('transactions',
			$this->paginateWithQuery($transactions)
		);
	}

	public function show (\App\Models\Models\SellerTransaction $transaction)
	{
		return view('admin.transactions.show')->with('transaction', $transaction);
	}
}