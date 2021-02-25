<?php

namespace App\Queries;

use App\Library\Utils\Extensions\Time;
use App\Models\Announcement;
use Illuminate\Support\Carbon;

class AnnouncementQuery extends AbstractQuery
{
    protected function model (): string
    {
        return Announcement::class;
    }

    public static function begin (): self
    {
        return new self();
    }

    public function displayable (): self
    {
        $current = now()->format(Time::MYSQL_FORMAT);
        $this->query->where('validFrom', '<=', $current)->where('validUntil', '>=', $current);
        return $this;
    }

    public function excludeDeleted (): self
    {
        $this->query->whereJsonDoesntContain('deletedBy', $this->user()->id);
        return $this;
    }

    public function excludeRead (): self
    {
        $this->query->whereJsonDoesntContain('readBy', $this->user()->id);
        return $this;
    }

    protected function user ()
    {
        return auth('seller-api')->user();
    }
}