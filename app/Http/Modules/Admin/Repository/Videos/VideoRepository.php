<?php

namespace App\Http\Modules\Admin\Repository\Videos;

class VideoRepository extends \App\Http\Modules\Shared\Repository\BaseRepository implements VideoRepositoryInterface
{
	public function allMoviesPaginated (int $chunk = 15) : \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		return $this->model->newQuery()->where('type', 'movie')->paginate($chunk);
	}
}