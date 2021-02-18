<?php

namespace App\Http\Modules\Admin\Repository\User\Customer\Contracts;

interface CustomerRepository extends \App\Http\Modules\Shared\Repository\RepositoryInterface
{
	public function recentPaginated (int $chunk = 15) : \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}