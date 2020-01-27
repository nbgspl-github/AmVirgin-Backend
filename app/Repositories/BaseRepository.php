<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;

abstract class BaseRepository implements RepositoryInterface{
	public function all(){

	}

	public function create(array $data){

	}

	public function update(array $data, $id){

	}

	public function delete($id){

	}

	public function show($id){

	}

	protected abstract function model(): string;
}