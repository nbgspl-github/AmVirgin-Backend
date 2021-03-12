<?php

namespace App\Http\Requests\Sliders\Shop;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
            'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
            'banner' => ['bail', 'required', 'mimes:jpg,jpeg,png,bmp'],
            'target' => ['bail', 'required', 'url'],
            'rating' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
            'active' => ['bail', 'required', 'boolean'],
        ];
    }

    public function validated (): array
    {
        return array_merge(parent::validated(), [
            'type' => 'external-link',
            'section' => 'shop'
        ]);
    }
}