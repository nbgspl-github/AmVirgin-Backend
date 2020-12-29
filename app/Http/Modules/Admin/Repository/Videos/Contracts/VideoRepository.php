<?php

namespace App\Http\Modules\Admin\Repository\Videos\Contracts;

interface VideoRepository
{
	public function allMoviesPaginated (int $chunk = 15) : \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}