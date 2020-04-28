<?php

namespace App\Resources\Announcements;

class Announcement extends \Illuminate\Http\Resources\Json\JsonResource{
	public function toArray($request){
		return [
			'title' => $this->title(),
			'content' => $this->content(),
			"banner" => $this->bannerUri(),
		];
	}

	protected function bannerUri(){
		return makeUrl($this->banner());
	}
}