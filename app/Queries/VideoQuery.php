<?php

namespace App\Queries;

use App\Library\Utils\Extensions\Str;
use App\Library\Utils\Extensions\Time;
use App\Models\MediaLanguage;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;

class VideoQuery extends AbstractQuery
{

	protected function model () : string
	{
		return Video::class;
	}

	public static function begin (): self
	{
		return new self();
	}

	public function displayable (): self
	{
		$this->query->where('pending', false);
		return $this;
	}

	public function trending (bool $yes = true): self
	{
		$this->query->where('trending', $yes);
		return $this;
	}

	public function genre ($genreId = null): self
	{
		if ($genreId != null)
			$this->query->where('genreId', $genreId);
		return $this;
	}

	public function section (int $sectionId): self
	{
		$this->query->where('sectionId', $sectionId);
		return $this;
	}

	public function movie (): self
	{
		$this->query->where('type', Video::Type['Movie']);
		return $this;
	}

	public function series (): self
	{
		$this->query->where('type', Video::Type['Series']);
		return $this;
	}

	public function isNew (): self
	{
		$lastWeek = Time::mysqlStamp(time() - 604800);
		$this->query->where('created_at', '>=', $lastWeek);
		return $this;
	}

	public function language ($languageId = null): self
	{
		$language = MediaLanguage::find($languageId);
		if ($language != null) {
			$language = $language->name;
			$this->query->where('languageSlug', 'LIKE', "%{$language}%");
		}
		return $this;
	}

	public function includeExplicit ($include = false): self
	{
		if ($include == true) {
			$this->query->whereIn('pgRating', ['G', 'PG', 'PG-13', 'R', 'NC-17']);
		} else {
			$this->query->whereIn('pgRating', ['G', 'PG', 'PG-13', 'R']);
		}
		return $this;
	}

	public function subscriptionType ($subscriptionType = null): self
	{
		if ($subscriptionType != null) {
			$this->query->where('subscriptionType', $subscriptionType);
		}
		return $this;
	}

	public function applyFilters (bool $apply = true): self
	{
		if ($apply) {
			$languages = Str::split(',', request('language'), true);
			if (count($languages) > 0) {
				$this->query->whereHas('sources', function (Builder $q) use ($languages) {
					$q->whereIn('mediaLanguageId', $languages);
				});
			}
			$genres = Str::split(',', request('genre'), true);
			if (count($genres) > 0) {
				$this->query->whereIn('genreId', $genres);
			}

			$subscription = Str::split(',', request('type'), true);
			if (count($subscription) > 0) {
				$this->query->whereIn('subscriptionType', $subscription);
			}

			$rating = (request('explicit', 0) == 1) ? ['G', 'PG', 'PG-13', 'R', 'NC-17'] : ['G', 'PG', 'PG-13', 'R'];
			$this->query->whereIn('pgRating', $rating);
		}
		return $this;
	}
}