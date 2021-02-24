<?php


namespace App\Http\Modules\Admin\Controllers\Web\Announcements;


use App\Models\Announcement;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdvertisementController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function index(): Renderable
	{
		return view('admin.announcements.index')->with('announcements',
			$this->paginateWithQuery(Announcement::query()->latest()->paginate($this->paginationChunk()))
		);
	}

	public function create(): Renderable
	{

	}

	public function edit(Announcement $announcement): RedirectResponse
	{

	}

	public function store(Request $request): RedirectResponse
	{

	}

	public function update(Request $request, Announcement $announcement): RedirectResponse
	{

	}

	public function delete(Announcement $announcement)
	{

	}
}
