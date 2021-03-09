<?php

namespace App\Http\Modules\Admin\Requests\Statistics;

use Illuminate\Database\Eloquent\Builder;

class IndexRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules (): array
    {
        return [
            'from' => 'bail|nullable|date_format:Y-m-d',
            'to' => 'bail|nullable|date_format:Y-m-d|after:from',
        ];
    }

    public function constrain (Builder $builder): Builder
    {
        $validated = parent::validated();
        if (isset($validated['from']) && isset($validated['to'])) {
            $builder = $builder->where('created_at', '>=', $validated['from'])->where('created_at', '<', $validated['to']);
        }
        return $builder;
    }
}