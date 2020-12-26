<?php

namespace App\Http\Modules\Admin\Repository\User\Seller\Contracts;

interface SellerRepository extends \App\Http\Modules\Shared\Repository\RepositoryInterface
{
	public function recentPaginated (int $chunk = 15) : \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}