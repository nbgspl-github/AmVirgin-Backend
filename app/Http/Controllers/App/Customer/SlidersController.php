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
use Illuminate\Support\Facades\Storage;

class SlidersController extends ResourceController{
	use FluentResponse;

	public function index(){
		$response = null;
		try {
			$all = $this->retrieveChildCollection(function ($query){
				$query->where('active', true);
			});
			$all->transform(function (Slider $slider){
				$payload = $slider->toArray();
				$payload['poster'] = Storage::disk('public')->url($slider->getPoster());
				return $payload;
			});
			if ($all->count() > 0) {
				$response = $this->success()->setValue('data', $all);
			}
			else {
				$response = $this->failed()->status(HttpNoContent);
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