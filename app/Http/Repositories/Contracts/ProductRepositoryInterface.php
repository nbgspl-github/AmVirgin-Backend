<?php

namespace App\Http\Repositories\Contracts;

interface ProductRepositoryInterface{

	public function list();

	public function listBySeller();

	public function create();

	public function modify();

	public function conceal();

	public function delete();
}