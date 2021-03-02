<?php

namespace App\Resources\Products\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    public function toArray ($request): array
    {
        return [
            'label' => $this->label,
            'interface' => $this->interface,
            'group' => $this->group,
            'value' => $this->value,
        ];
    }
}