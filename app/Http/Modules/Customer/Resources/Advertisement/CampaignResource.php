<?php

namespace App\Http\Modules\Customer\Resources\Advertisement;

use App\Library\Enums\News\Article\Types;

class CampaignResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    public function toArray ($request): array
    {
        return $this->type->is(Types::Article)
            ? $this->article()
            : $this->video();
    }

    protected function video (): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'thumbnail' => $this->thumbnail,
            'video' => $this->video
        ];
    }

    protected function article (): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail,
        ];
    }
}