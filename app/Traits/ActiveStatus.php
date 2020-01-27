<?php

namespace App\Traits;

trait ActiveStatus{
	/**
	 * Gets whether a user is active and is allowed to access services.
	 * @return bool
	 */
	public function isActive(){
		return $this->active;
	}

	/**
	 * Sets whether a user is active, setting this to false will prevent user from logging in.
	 * @param bool $active
	 * @return $this
	 */
	public function setActive(bool $active){
		$this->active = $active;
		return $this;
	}
}