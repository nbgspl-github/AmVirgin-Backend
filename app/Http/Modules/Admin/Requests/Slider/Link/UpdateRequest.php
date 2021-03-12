<?php

namespace App\Http\Modules\Admin\Requests\Slider\Link;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'title' => ['bail', 'required', 'string', 'min:1', 'max:256'],
            'description' => ['bail', 'required', 'string', 'min:1', 'max:2048'],
            'banner' => ['bail', 'nullable', 'mimes:jpg,jpeg,png,bmp'],
            'targetLink' => ['bail', 'required_if:type,external-link', 'url'],
            'rating' => ['bail', 'nullable', 'numeric', 'min:0', 'max:5'],
            'active' => ['bail', 'required', 'boolean'],
        ];
    }

    public function validated (): array
    {
        return array_merge(parent::validated(), [

        ]);
    }
}