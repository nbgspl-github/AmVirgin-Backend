<?php

namespace App\Http\Requests\Sliders\Shop;

use App\Models\Slider;

class StatusRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'id' => ['bail', 'required', Slider::exists()],
            'active' => ['bail', 'required', 'boolean'],
        ];
    }

    public function validated (): array
    {
        return array_merge(parent::validated(), [

        ]);
    }
}