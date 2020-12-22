<?php

namespace App\Http\Controllers\Api\Customer\Entertainment;

use App\Http\Controllers\Api\ApiController;
use App\Models\Video\Source;

class LanguageListController extends ApiController
{
	public function index ()
	{
		$availableLanguages = Source::query()->select('mediaLanguageId')->distinct()->get();
		$availableLanguages->transform(function (Source $videoSource) {
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
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing available media languages.')->setValue('payload', $availableLanguages)->send();
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}