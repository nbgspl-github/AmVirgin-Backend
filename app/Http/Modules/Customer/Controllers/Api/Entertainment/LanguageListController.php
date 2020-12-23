<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Models\Video\Source;

class LanguageListController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function index () : \Illuminate\Http\JsonResponse
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
}