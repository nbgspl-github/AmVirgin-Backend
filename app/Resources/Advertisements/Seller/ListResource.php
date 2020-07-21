<?php


namespace App\Resources\Advertisements\Seller;


use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'key' => $this->id,
            'subject' => $this->subject,
            'message' => $this->message,
            'image' => $this->banner,
            'active' => $this->active
        ];
    }
}