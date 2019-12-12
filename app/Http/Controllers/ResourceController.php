<?php

namespace App\Http\Controllers\Base;

use App\Exceptions\ResourceConflictException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Attributes\AttributeCollection;
use App\Http\Resources\Attributes\AttributeResource;
use App\Traits\FluentResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Collection;

abstract class ResourceController extends BaseController{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	use FluentResponse;

	/**
	 * @return Model
	 */
	protected abstract function parentProvider();

	/**
	 * @return Model
	 */
	protected abstract function provider();

	/**
	 * @return AttributeResource
	 */
	protected abstract function resourceConverter(Model $model);

	/**
	 * @return AttributeCollection
	 */
	protected abstract function collectionConverter(Collection $collection);

	protected function throwIfExists($id){
		$model = $this->provider()::find($id);
		if ($model != null)
			throw new ResourceConflictException();
		else
			return true;
	}

	protected function throwIfParentExists($id){
		$model = $this->parentProvider()::find($id);
		if ($model != null)
			throw new ResourceConflictException();
		else
			return true;
	}

	protected function throwIfNotExists($id){
		$model = $this->provider()::find($id);
		if ($model != null)
			return $model;
		else
			throw new ResourceNotFoundException();
	}

	protected function throwIfParentNotExists($id){
		$model = $this->parentProvider()::find($id);
		if ($model != null)
			return $model;
		else
			throw new ResourceNotFoundException();
	}
}
