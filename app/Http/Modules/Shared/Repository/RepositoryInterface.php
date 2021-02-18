<?php

namespace App\Http\Modules\Shared\Repository;

interface RepositoryInterface
{
	/**
	 * Retrieve all records in the repository.
	 * @return mixed
	 */
	public function list ();

	/**
	 * Create a record in the repository.
	 * @param array $attributes
	 * @return \App\Library\Database\Eloquent\Model
	 */
	public function create (array $attributes) : \App\Library\Database\Eloquent\Model;

	/**
	 * Update a record in the repository.
	 * @param \App\Library\Database\Eloquent\Model $model
	 * @param array $attributes
	 * @return \App\Library\Database\Eloquent\Model
	 */
	public function update (\App\Library\Database\Eloquent\Model $model, array $attributes) : \App\Library\Database\Eloquent\Model;

	/**
	 * Deletes a record from the repository.
	 * @param \App\Library\Database\Eloquent\Model $model
	 * @return bool
	 */
	public function delete (\App\Library\Database\Eloquent\Model $model) : bool;
}