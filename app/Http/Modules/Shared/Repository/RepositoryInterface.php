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
}