<?php

namespace App\Http\Controllers\Api\Customer\Entertainment;

use App\Http\Controllers\Api\ApiController;
use App\Models\VideoSource;

class LanguageListController extends ApiController
{
	public function index ()
	{
		$availableLanguages = VideoSource::query()->select('mediaLanguageId')->distinct()->get();
		$availableLanguages->transform(function (VideoSource $videoSource) {
			$language = $videoSource->language;
			if ($language != null) {
				return (object)[
					'id' => $language->id,
					'name' => $language->name,
					'code' => $language->code
				];
			} else {
				return $language;
			}
		});
		$availableLanguages = $availableLanguages->filter()->values();
		return $this->responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing available media languages.')->setValue('payload', $availableLanguages)->send();
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}