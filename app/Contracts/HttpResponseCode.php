<?php

namespace App\Contracts;

interface HttpResponseCode {
	public function withResponseCode(int $responseCode);
}