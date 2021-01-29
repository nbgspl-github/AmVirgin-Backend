<?php

namespace App\Http\Modules\Admin\Controllers\Web;

class DashboardController extends WebController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$entries = $this->entries();
		$response = view('admin.home.dashboard');
		foreach ($entries as $key => $value) {
			$response = $response->with($key, $value);
		}
		return $response;
	}

	protected function entries () : array
	{
		return [
			'customers' => $this->customers(),
			'sellers' => $this->sellers(),
			'articles' => $this->articles(),
			'videos' => $this->videos(),
			'ordersToday' => $this->ordersToday(),
			'salesToday' => $this->salesToday(),
			'customersToday' => $this->customersToday(),
			'sellersToday' => $this->sellersToday(),
		];
	}

	protected function customers () : \Illuminate\Support\Collection
	{
		return \App\Models\Auth\Customer::query()->latest()->limit(15)->get();
	}

	protected function sellers () : \Illuminate\Support\Collection
	{
		return \App\Models\Auth\Seller::query()->latest()->limit(15)->get();
	}

	protected function articles () : \Illuminate\Support\Collection
	{
		return \App\Models\News\Article::query()->orderByDesc('views')->limit(15)->get();
	}

	protected function videos () : \Illuminate\Support\Collection
	{
		return \App\Models\Video\Video::query()->orderByDesc('hits')->limit(15)->get();
	}

	protected function customersToday () : int
	{
		return \App\Models\Auth\Customer::query()->whereBetween('created_at', [
			now()->startOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
			now()->endOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
		])->count();
	}

	protected function sellersToday () : int
	{
		return \App\Models\Auth\Seller::query()->whereBetween('created_at', [
			now()->startOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
			now()->endOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
		])->count();
	}

	protected function ordersToday () : int
	{
		return \App\Models\Order\Order::query()->whereBetween('created_at', [
			now()->startOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
			now()->endOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
		])->count();
	}

	protected function salesToday () : int
	{
		return \App\Models\Order\Order::query()->whereBetween('created_at', [
			now()->startOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
			now()->endOfDay()->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT),
		])->sum('total');
	}
}