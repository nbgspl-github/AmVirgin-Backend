<?php

namespace App\Http\Controllers\Base;

use App\Exceptions\ResourceConflictException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

	public function __construct(){
		parent::__construct();
	}

	/**
	 * @return Model
	 */
	protected abstract function parentProvider();

	/**
	 * @return Model
	 */
	protected abstract function provider();

//	/**
//	 * @param Model $model
//	 * @return AttributeResource
//	 */
	protected abstract function resourceConverter(Model $model);

//	/**
//	 * @param Collection $collection
//	 * @return AttributeCollection
//	 */
	protected abstract function collectionConverter(Collection $collection);

	protected abstract function guard();

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
			throw new ModelNotFoundException();
	}

	protected function throwIfParentNotExists($id){
		$model = $this->parentProvider()::find($id);
		if ($model != null)
			return $model;
		else
			throw new ModelNotFoundException();
	}

	protected function user(){
		return $this->guard()->user();
	}

	protected function evaluate(callable $closure, ...$arguments){
		return call_user_func($closure, $arguments);
	}

	/**
	 * @param $value callable|integer|array
	 * @return Model|null
	 */
	protected function retrieveChild($value){
		$type = gettype($value);
		if ($type == 'callable') {
			return $this->provider()::where($value)->firstOrFail();
		}
		else if ($type == 'integer') {
			return $this->provider()::findOrFail($value);
		}
		else {
			return $this->provider()::where($value)->firstOrFail();
		}
	}

	/**
	 * @param $value callable|integer|array
	 * @return Model|null
	 */
	protected function retrieveParent($value){
		$type = gettype($value);
		if ($type == 'callable') {
			return $this->parentProvider()::where($value)->firstOrFail();
		}
		else if ($type == 'integer') {
			return $this->parentProvider()::findOrFail($value);
		}
		else {
			return $this->parentProvider()::where($value)->firstOrFail();
		}
	}

	/**
	 * @param $value callable|array
	 * @return Collection
	 */
	protected function retrieveChildCollection($value){
		$type = gettype($value);
		if ($type == 'callable') {
			return $this->provider()::where($value)->get();
		}
		else {
			return $this->provider()::where($value)->get();
		}
	}

	/**
	 * @param $value callable|integer|array
	 * @return Model|null
	 */
	protected function retrieveParentCollection($value){
		$type = gettype($value);
		if ($type == 'callable') {
			return $this->parentProvider()::where($value)->get();
		}
		else {
			return $this->parentProvider()::where($value)->get();
		}
	}
}
