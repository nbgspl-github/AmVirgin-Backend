<?php

namespace App\Http\Modules\Shared\Repository;

class BaseRepository implements RepositoryInterface
{
	/**
	 * @var $model \App\Library\Database\Eloquent\Model
	 */
	protected $model;

	public function __construct (\App\Library\Database\Eloquent\Model $model)
	{
		$this->model = $model;
	}

	public function list ()
	{
		return $this->model->all();
	}

	public function create (array $attributes) : \App\Library\Database\Eloquent\Model
	{
		return $this->model->create($attributes);
	}
}