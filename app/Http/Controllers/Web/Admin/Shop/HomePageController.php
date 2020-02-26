<?php

namespace App\Http\Controllers\Web\Admin\Shop;

use App\Http\Controllers\BaseController;

class HomePageController extends BaseController {
	public function __construct() {
		parent::__construct();
	}

	public function choices() {
		return view('admin.shop.home-page.choices');
	}

	public function editSaleOfferTimerDetails() {
		return view('admin.shop.home-page.sale-offer-timer.edit');
	}

	public function edit() {

	}
}