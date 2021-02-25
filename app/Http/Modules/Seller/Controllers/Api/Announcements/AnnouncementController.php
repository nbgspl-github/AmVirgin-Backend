<?php

namespace App\Http\Modules\Seller\Controllers\Api\Announcements;

use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Rule;
use App\Models\Announcement;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'mark' => [
				'action' => ['bail', 'required', Rule::in(['read', 'delete', 'unread'])],
			],
		];
	}

	public function index () : JsonResponse
	{
        $announcementCollection = Announcement::startQuery()->displayable()->excludeDeleted()->get();
        dd($announcementCollection);
        $resourceCollection = \App\Resources\Announcements\Announcement::collection($announcementCollection);
        return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)
            ->message('Listing all announcements.')
            ->setValue('payload', $resourceCollection)
            ->send();
    }

	public function mark ($id) : JsonResponse
	{
		$response = responseApp();
		$validated = $this->requestValid(request(), $this->rules['mark']);
		$announcement = Announcement::startQuery()->key($id)->excludeDeleted()->firstOrFail();
		switch ($validated['action']) {
			case 'read':
				$readBy = $announcement->readBy;
				if (!Arrays::contains($readBy, $this->seller()->id)) {
					Arrays::push($readBy, $this->seller()->id);
				}
				$announcement->update(['readBy' => $readBy]);
				break;

			case 'unread':
				$readBy = $announcement->readBy;
				$id = $this->seller()->id;
				$readBy = collect($readBy)->filter(function ($value, $key) use ($id) {
					return $value != $id;
				})->toArray();
				$announcement->update(['readBy' => $readBy]);
				break;

			case 'delete':
				$deletedBy = $announcement->deletedBy;
				if (!Arrays::contains($deletedBy, $this->seller()->id)) {
					Arrays::push($deletedBy, $this->seller()->id);
				}
				$announcement->update(['deletedBy' => $deletedBy]);
				break;
		}
		return responseApp()->prepare(
			null,
			\Illuminate\Http\Response::HTTP_OK,
			'Marked announcement status successfully.'
		);
	}
}