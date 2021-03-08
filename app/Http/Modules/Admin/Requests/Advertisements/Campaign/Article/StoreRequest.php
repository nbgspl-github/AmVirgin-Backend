<?php

namespace App\Http\Modules\Admin\Requests\Advertisements\Campaign\Article;

use App\Library\Enums\News\Article\Types;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'title' => 'bail|required|string|max:255',
            'content' => 'bail|nullable|string|max:1000000',
            'thumbnail' => 'bail|image|max:2048',
        ];
    }

    public function validated (): array
    {
        return array_merge(parent::validated(), [
            'type' => Types::Article
        ]);
    }
}