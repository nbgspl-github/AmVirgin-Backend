<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Base\ResourceController;
use App\Interfaces\StatusCodes;
use App\Models\Slider;
use App\Traits\FluentResponse;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SlidersController extends ResourceController{
	use FluentResponse;

	public function index(){
		$customerId = $this->user()->getKey();
		$response = null;
		try {
			$all = $this->retrieveChildCollection(function ($query){
				$query->where('active', true);
			});
			if ($all->count() > 0) {
				$response = $this->success()->setValue('data', $all);
			}
			else {
				$response = $this->failed()->status(StatusCodes::NoContent);
			}
		}
		catch (Exception $exception) {
			$response = $this->error();
		}
		finally {
			return $response->send();
		}
	}

	protected function parentProvider(){
		return null;
	}

	protected function provider(){
		return Slider::class;
	}

	protected function resourceConverter(Model $model){

	}

	protected function collectionConverter(Collection $collection){

	}

	protected function guard(){
		return Auth::guard('customer-api');
	}
}