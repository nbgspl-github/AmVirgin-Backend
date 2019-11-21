<?php

namespace App\Http\Resources;

use App\Contracts\HttpResponseCode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource implements HttpResponseCode {
	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request) {
		return parent::toArray($request);
	}

	public function withResponseCode(int $responseCode) {

	}
}