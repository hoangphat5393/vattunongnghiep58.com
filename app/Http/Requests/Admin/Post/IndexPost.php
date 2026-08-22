<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.post.index');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'orderBy' => 'in:id,post_status,post__type,meta_title,meta_description|nullable',

            'orderDirection' => 'in:asc,desc|nullable',

            'search' => 'string|nullable',

            'page' => 'integer|nullable',

            'per_page' => 'integer|nullable',

        ];

    }
}
