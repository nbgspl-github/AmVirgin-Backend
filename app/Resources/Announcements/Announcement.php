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
                'read' => $this->readBy,
                'deleted' => $this->deletedBy,
            ],
        ];
    }

    protected function sellerId (): int
    {
        return auth('seller-api')->id();
    }
}