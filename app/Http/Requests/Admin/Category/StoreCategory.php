<?php

namespace App\Http\Requests\Admin\Category;

use Brackets\Translatable\TranslatableFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.category.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'category_name' => ['required'],
            'category_slug' => ['required'],
            'category_description' => ['nullable'],
            'parent_id' => ['nullable'],
            'meta_title' => ['nullable'],
            'meta_description' => ['nullable'],
            'properties' => ['nullable', 'array'],
            'news_type' => ['required']
        ];
    }

    /**
    * Modify input data
    *
    * @return array
    */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();

        $sanitized['properties'] = collect( $sanitized['properties'] )->pluck('id')->toArray();
        //Add your code for manipulation with request data here
        return $sanitized;
    }

}
