<?php

namespace App\Contracts;

interface SerializesToArray {

	/**
	 * Gets an array containing all exposed attributes of this class,
	 * excluding the ones mentioned to redact.
	 * @param array $redact
	 * @return array
	 */
	public function serializeArray($redact = []);
}