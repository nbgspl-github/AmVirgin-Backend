<?php

namespace App\Resources\Filters\Customer\Entertainment;

use App\Models\Country;
use App\Models\Genre;
use App\Models\VideoSource;
use App\Resources\Filters\Customer\Entertainment\Country\ListResource as CountryList;
use App\Resources\Filters\Customer\Entertainment\Genre\ListResource as GenreList;
use App\Resources\Filters\Customer\Entertainment\Language\ListResource as LanguageList;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentFilterResource extends JsonResource
{
	public function toArray ($request)
	{
		return [
			'age' => $this->age(),
			'rental' => $this->rental(),
			'genre' => $this->genres(),
			'language' => $this->languages(),
			'country' => $this->countries()
		];
	}

	protected function age ()
	{
		return [
			'Above 18',
			'Below 18'
		];
	}

	protected function rental ()
	{
		return [
			'Rental'
		];
	}

	protected function genres ()
	{
		return GenreList::collection(Genre::all());
	}

	protected function countries ()
	{
		return CountryList::collection(Country::all());
	}

	protected function languages ()
	{
		$availableLanguages = VideoSource::query()->select('mediaLanguageId')->distinct()->get();
		$availableLanguages->transform(function (VideoSource $videoSource) {
			$language = $videoSource->language;
			return $language;
		});
		$availableLanguages = $availableLanguages->filter()->values();
		return LanguageList::collection($availableLanguages);
	}
}