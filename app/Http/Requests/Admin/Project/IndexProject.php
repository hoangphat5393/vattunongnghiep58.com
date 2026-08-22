<?php

namespace App\Http\Requests\Admin\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.project.index');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'orderBy' => 'in:id,title,gia,gia_m,gia_thue,status,city_id,district_id,ward_id,street,lat,lng,meta_title,meta_description,meta_keyword,category_id,investor_id|nullable',

            'orderDirection' => 'in:asc,desc|nullable',

            'search' => 'string|nullable',

            'page' => 'integer|nullable',

            'per_page' => 'integer|nullable',

        ];

    }
}
