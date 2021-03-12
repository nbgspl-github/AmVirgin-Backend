<?php

namespace App\Resources\Sliders;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray ($request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'banner' => $this->banner,
            'rating' => $this->rating,
            'type' => $this->type,
            'target' => $this->target,
        ];
    }
}