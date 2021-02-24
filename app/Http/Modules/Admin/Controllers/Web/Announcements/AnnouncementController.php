<?php


namespace App\Http\Modules\Admin\Controllers\Web\Announcements;


use App\Http\Modules\Admin\Requests\Announcement\StoreRequest;
use App\Models\Announcement;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnnouncementController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	public function __construct()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function index(): Renderable
	{
		return view('admin.announcements.index')->with('announcements',
			$this->paginateWithQuery(Announcement::query()->latest())
		);
	}

	public function create(): Renderable
	{
		return view('admin.announcements.create');
	}

	public function edit(Announcement $announcement): Renderable
	{
		return view('admin.announcements.edit')->with('announcement', $announcement);
	}

	public function store(StoreRequest $request): RedirectResponse
	{
		Announcement::query()->create($request->validated());
		return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully.');
	}

	public function update(Request $request, Announcement $announcement): RedirectResponse
	{

	}

	/**
	 * @param Announcement $announcement
	 * @return JsonResponse
	 * @throws Exception
	 */
	public function delete(Announcement $announcement): JsonResponse
	{
		$announcement->delete();
		return responseApp()->prepare(
			[], \Illuminate\Http\Response::HTTP_OK, 'Announcement deleted successfully.'
		);
	}
}
