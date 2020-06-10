<?php

namespace App\Contracts;

use App\Traits\RedactArrayItems;

interface SerializesToJson {

	/**
	 * Gets a JsonObject containing all exposed attributes of this class,
	 * excluding the ones mentioned to redact.
	 * @param array $redact
	 * @return array
	 */
	public function serializeJson($redact = []);
}