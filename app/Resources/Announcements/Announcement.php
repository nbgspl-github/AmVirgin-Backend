<?php

namespace App\Resources\Announcements;

use App\Library\Utils\Extensions\Arrays;
use Illuminate\Http\Resources\Json\JsonResource;

class Announcement extends JsonResource
{
    public function toArray ($request): array
    {
        return [
            'key' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            "banner" => $this->banner,
            "extra" => [
                'read' => Arrays::contains($this->readBy, $this->sellerId()),
                'deleted' => Arrays::contains($this->deletedBy, $this->sellerId()),
            ],
        ];
    }

    protected function sellerId (): int
    {
        return auth('seller-api')->id();
    }
}