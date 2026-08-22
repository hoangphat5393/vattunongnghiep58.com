<?php

namespace App\Http\Requests\Admin\Direction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexDirection extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.direction.index');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'orderBy' => 'in:id,value,status|nullable',

            'orderDirection' => 'in:asc,desc|nullable',

            'search' => 'string|nullable',

            'page' => 'integer|nullable',

            'per_page' => 'integer|nullable',

        ];

    }
}
