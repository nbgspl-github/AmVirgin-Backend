<?php

namespace App\Queries;

use App\Library\Utils\Extensions\Str;
use App\Library\Utils\Extensions\Time;
use App\Models\Video\Language;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Builder;

class VideoQuery extends AbstractQuery
{

    protected function model (): string
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
        $this->query->orderByDesc('hits');
        return $this;
    }

    public function genre ($genreId = null): self
    {
        if ($genreId != null)
            $this->query->where('genre_id', $genreId);
        return $this;
    }

    public function section (int $sectionId): self
    {
        $this->query->whereJsonContains('sections', $sectionId);
        return $this;
    }

    public function movie (): self
    {
        $this->query->where('type', 'movie');
        return $this;
    }

    public function series (): self
    {
        $this->query->where('type', 'series');
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
        $language = Language::find($languageId);
        if ($language != null) {
            $language = $language->name;
            $this->query->where('languageSlug', 'LIKE', "%{$language}%");
        }
        return $this;
    }

    public function includeExplicit ($include = false): self
    {
        if ($include == true) {
            $this->query->whereIn('pg_rating', ['G', 'PG', 'PG-13', 'R', 'NC-17']);
        } else {
            $this->query->whereIn('pg_rating', ['G', 'PG', 'PG-13', 'R']);
        }
        return $this;
    }

    public function subscriptionType ($subscriptionType = null): self
    {
        if ($subscriptionType != null) {
            $this->query->where('subscription_type', $subscriptionType);
        }
        return $this;
    }

    public function applyFilters (bool $apply = true): self
    {
        if ($apply) {
            $languages = Str::split(',', request('language'), true);
            if (count($languages) > 0) {
                $this->query->whereHas('audios', function (Builder $q) use ($languages) {
                    $q->whereIn('video_language_id', $languages);
                });
            }
            $genres = Str::split(',', request('genre'), true);
            if (count($genres) > 0) {
                $this->query->whereIn('genre_id', $genres);
            }

            $subscription = Str::split(',', request('type'), true);
            if (count($subscription) > 0) {
                $this->query->whereIn('subscription_type', $subscription);
            }

            $rating = (request('explicit', 0) == 1) ? ['G', 'PG', 'PG-13', 'R', 'NC-17'] : ['G', 'PG', 'PG-13', 'R'];
            $this->query->whereIn('pg_rating', $rating);
        }
        return $this;
    }
}
