<?php

namespace App\Http\Modules\Admin\Requests\Advertisements\Campaign\Video;

use App\Library\Enums\News\Article\Types;

class UpdateRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'title' => 'bail|required|string|max:255',
            'thumbnail' => 'bail|image|max:2048',
            'video' => 'bail|nullable|mimes:mp4,mkv|max:10000',
        ];
    }
}