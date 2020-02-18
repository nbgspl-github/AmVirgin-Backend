<?php

namespace App\Http\Controllers\App\Customer\Cart;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use Illuminate\Validation\Rule;
use Throwable;

class QuoteController extends ExtendedResourceController {

	protected $rules = [];

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'add' => [
				'productId' => ['bail', 'required', Rule::exists(Tables::Products, 'id')],
				'quantity' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10'],
			],
		];
	}

	public function retrieve() {
		try {

		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}

	public function add() {

	}

	public function update() {

	}

	public function remove() {

	}

	protected function guard() {
		return auth('customer-api');
	}
}