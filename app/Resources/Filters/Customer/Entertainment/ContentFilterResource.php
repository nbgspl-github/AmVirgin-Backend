<?php

namespace App\Resources\Filters\Customer\Entertainment;

use App\Models\Country;
use App\Models\Video\Genre;
use App\Resources\Filters\Customer\Entertainment\Country\ListResource as CountryList;
use App\Resources\Filters\Customer\Entertainment\Genre\ListResource as GenreList;
use App\Resources\Filters\Customer\Entertainment\Language\ListResource as LanguageList;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentFilterResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'age' => $this->age(),
			'type' => $this->type(),
			'genre' => $this->genres(),
			'language' => $this->languages(),
			'country' => $this->countries()
		];
	}

	protected function age () : array
	{
		return [
			[
				'key' => 0,
				'value' => 'Below 18'
			],
			[
				'key' => 1,
				'value' => 'Above 18'
			],
		];
	}

	protected function type () : array
	{
		return [
			[
				'key' => 'free',
				'value' => 'Free'
			],
			[
				'key' => 'paid',
				'value' => 'Paid'
			],
			[
				'key' => 'subscription',
				'value' => 'Subscription'
			]
		];
	}

	protected function genres () : AnonymousResourceCollection
	{
		return GenreList::collection(Genre::all());
	}

	protected function countries () : AnonymousResourceCollection
	{
		return CountryList::collection(Country::all());
	}

	protected function languages () : AnonymousResourceCollection
	{
		$availableLanguages = \App\Models\Models\Video\Audio::query()->select('video_language_id')->distinct()->get();
		$availableLanguages->transform(function (\App\Models\Models\Video\Audio $audio) {
			return $audio->language;
		});
		$availableLanguages = $availableLanguages->filter()->values();
		return LanguageList::collection($availableLanguages);
	}
}