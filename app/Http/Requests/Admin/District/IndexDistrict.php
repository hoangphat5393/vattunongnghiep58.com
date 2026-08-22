<?php

namespace App\Http\Requests\Admin\District;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexDistrict extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.district.index');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'orderBy' => 'in:id,name,type,lat,lng,city_id|nullable',

            'orderDirection' => 'in:asc,desc|nullable',

            'search' => 'string|nullable',

            'page' => 'integer|nullable',

            'per_page' => 'integer|nullable',

        ];

    }
}
