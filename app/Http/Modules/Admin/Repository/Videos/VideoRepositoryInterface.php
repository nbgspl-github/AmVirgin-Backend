<?php

namespace App\Http\Modules\Admin\Repository\Videos;

interface VideoRepositoryInterface
{
	public function allMoviesPaginated (int $chunk = 15) : \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}