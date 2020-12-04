<?php

namespace App\Http\Controllers\App\Customer\Entertainment;

use App\Http\Controllers\AppController;
use App\Models\VideoSource;

class LanguageListController extends AppController
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
		return $this->response()->status(HttpOkay)->message('Listing available media languages.')->setValue('payload', $availableLanguages)->send();
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}