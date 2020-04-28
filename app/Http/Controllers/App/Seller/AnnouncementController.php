<?php

namespace App\Http\Controllers\App\Seller;

use App\Models\Announcement;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends \App\Http\Controllers\Web\ExtendedResourceController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [];
	}

	public function index(): JsonResponse{
		$announcementCollection = Announcement::startQuery()->displayable()->get();
		$resourceCollection = \App\Resources\Announcements\Announcement::collection($announcementCollection);
		return responseApp()->status(HttpOkay)
			->message('Listing all announcements.')
			->setValue('payload', $resourceCollection)->send();
	}

	protected function guard(){
		return auth(self::SellerAPI);
	}
}