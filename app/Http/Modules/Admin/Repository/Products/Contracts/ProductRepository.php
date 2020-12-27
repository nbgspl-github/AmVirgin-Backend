<?php

namespace App\Http\Modules\Admin\Repository\Products\Contracts;

interface ProductRepository extends \App\Http\Modules\Shared\Repository\RepositoryInterface
{
	public function allProductsPaginated (int $chunk = 15);
}