<?php

namespace App\Http\Modules\Admin\Repository\Orders\Contracts;

interface OrderRepository extends \App\Http\Modules\Shared\Repository\RepositoryInterface
{
	public function getWithAllOrdersPaginated ();
}