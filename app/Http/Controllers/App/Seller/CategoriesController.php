<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Base\ResourceController;
use App\Http\Resources\Attributes\AttributeCollection;
use App\Http\Resources\Attributes\AttributeResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CategoriesController extends ResourceController{

	protected function provider(){
		// TODO: Implement providerModel() method.
	}

	/**
	 * @inheritDoc
	 */
	protected function parentProvider(){
		// TODO: Implement parentProvider() method.
	}

	protected function guard(){
		// TODO: Implement guard() method.
	}

	protected function resourceConverter(Model $model){
		// TODO: Implement resourceConverter() method.
	}

	protected function collectionConverter(Collection $collection){
		// TODO: Implement collectionConverter() method.
	}
}