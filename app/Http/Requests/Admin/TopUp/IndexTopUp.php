<?php

namespace App\Http\Requests\Admin\TopUp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexTopUp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.top-up.index');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'orderBy' => 'in:id,money,user_id,status|nullable',

            'orderDirection' => 'in:asc,desc|nullable',

            'search' => 'string|nullable',

            'page' => 'integer|nullable',

            'per_page' => 'integer|nullable',

        ];

    }
}
