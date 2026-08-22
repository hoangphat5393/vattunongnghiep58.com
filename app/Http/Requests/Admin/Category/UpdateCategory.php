<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCategory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.category.edit', $this->category);

    }

    /**
     * Get the validation rules that apply to the request.
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

            'news_type' => ['required'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        $sanitized['properties'] = collect($sanitized['properties'])->pluck('id')->toArray();

        return $sanitized;

    }
}
