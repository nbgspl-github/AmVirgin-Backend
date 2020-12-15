<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Exceptions\ValidationException;
use App\Models\Announcement;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends \App\Http\Controllers\WebServices\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'mark' => [
				'action' => ['bail', 'required', Rule::in(['read', 'delete', 'unread'])],
			],
		];
	}

	public function index (): JsonResponse
	{
		$announcementCollection = Announcement::startQuery()->displayable()->excludeDeleted()->get();
		$resourceCollection = \App\Resources\Announcements\Announcement::collection($announcementCollection);
		return responseApp()->status(HttpOkay)
			->message('Listing all announcements.')
			->setValue('payload', $resourceCollection)
			->send();
	}

	public function mark ($id)
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['mark']);
			$announcement = Announcement::startQuery()->key($id)->excludeDeleted()->firstOrFail();
			switch ($validated['action']) {
				case 'read':
					$readBy = $announcement->readBy();
					if (!Arrays::containsValueIndexed($readBy, $this->guard()->id())) {
						Arrays::push($readBy, $this->guard()->id());
					}
					$announcement->readBy($readBy);
					$announcement->save();
					break;

				case 'unread':
					$readBy = $announcement->readBy();
					$id = $this->guard()->id();
					$readBy = collect($readBy)->filter(function ($value, $key) use ($id) {
						return $value != $id;
					})->toArray();
					$announcement->readBy($readBy);
					$announcement->save();
					break;

				case 'delete':
					$deletedBy = $announcement->deletedBy();
					if (!Arrays::containsValueIndexed($deletedBy, $this->guard()->id())) {
						Arrays::push($deletedBy, $this->guard()->id());
					}
					$announcement->deletedBy($deletedBy);
					$announcement->save();
					break;
			}
			$response->status(HttpOkay)->message('Marked announcement status successfully.');
		} catch (ValidationException $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		} catch (\Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}