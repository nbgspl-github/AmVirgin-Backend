<?php

namespace App\Http\Modules\Admin\Requests\Genre;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'min:1', 'max:100', \App\Models\Video\Genre::unique('name')],
            'poster' => ['bail', 'nullable', 'image'],
            'active' => ['bail', 'required', 'boolean'],
        ];
    }
}