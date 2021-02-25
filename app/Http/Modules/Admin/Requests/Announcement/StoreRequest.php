<?php

namespace App\Http\Modules\Admin\Requests\Announcement;

class StoreRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'title' => 'bail|required|string|max:255',
            'content' => 'bail|required|string|max:5000',
            'banner' => 'bail|required|image|max:2048',
            'validFrom' => 'bail|required|date',
            'validUntil' => 'bail|required|date|after:validFrom',
        ];
    }
}