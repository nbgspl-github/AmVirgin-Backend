<?php

namespace App\Traits;

trait ExposeEventData{
	protected $data;

	public function eventData(){
		return $this->data;
	}

	protected function setEventData($data){
		$this->data = $data;
		return $this;
	}
}