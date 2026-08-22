<?php

namespace App\Http\Requests\Admin\Taxonomy;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreTaxonomy extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.taxonomy.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'taxonomy_name' => ['required', 'string'],

            'taxonomy_slug' => ['required', 'string'],

            'taxonomy_description' => ['nullable', 'string'],

            'parent_id' => ['nullable', 'integer'],

            'meta_title' => ['nullable'],

            'meta_description' => ['nullable'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        // Add your code for manipulation with request data here

        return $sanitized;

    }
}
