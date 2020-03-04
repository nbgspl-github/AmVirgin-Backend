<?php

namespace App\Http\Controllers\App\Customer;

use App\Constants\PageSectionType;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use Illuminate\Validation\Rule;
use Throwable;

class GlobalSearchController extends ExtendedResourceController {
	protected array $rules;

	public function __construct() {
		parent::__construct();
		$this->rules = [
			'search' => [
				'type' => ['bail', 'required', Rule::in(PageSectionType::Entertainment, PageSectionType::Shop)],
				'categoryId' => ['bail', 'nullable', Rule::exists(Tables::Categories, 'id')],
				'key' => ['bail', 'required', 'string', 'min:2', 'max:50'],
			],
		];
	}

	public function search() {
		try {

		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}

	protected function guard() {
		return auth('customer-api');
	}
}