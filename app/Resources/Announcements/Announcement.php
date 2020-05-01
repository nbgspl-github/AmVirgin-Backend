<?php

namespace App\Resources\Announcements;

use App\Classes\Arrays;

class Announcement extends \Illuminate\Http\Resources\Json\JsonResource{
	public function toArray($request){
		return [
			'key' => $this->id(),
			'title' => $this->title(),
			'content' => $this->content(),
			"banner" => $this->bannerUri(),
			"extra" => [
				'read' => Arrays::containsValueIndexed($this->readBy(), $this->sellerId()),
				'deleted' => Arrays::containsValueIndexed($this->deletedBy(), $this->sellerId()),
			],
		];
	}

	protected function bannerUri(){
		return makeUrl($this->banner());
	}

	protected function sellerId(){
		$user = auth('seller-api')->user();
		return $user != null ? $user->id() : -1;
	}
}