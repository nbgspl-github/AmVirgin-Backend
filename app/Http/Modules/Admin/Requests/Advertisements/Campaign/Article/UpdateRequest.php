<?php

namespace App\Http\Modules\Admin\Requests\Advertisements\Campaign\Article;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'title' => 'bail|required|string|max:255',
            'content' => 'bail|nullable|string|max:1000000',
            'thumbnail' => 'bail|nullable|image|max:2048',
        ];
    }
}