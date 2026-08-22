<?php

namespace App\Http\Requests\Admin\Functional;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateFunctional extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.functional.edit', $this->functional);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'value' => ['required', 'string'],

            // 'slug' => ['sometimes', Rule::unique('functionals', 'slug')->ignore($this->functional->getKey(), $this->functional->getKeyName()), 'string'],

            'status' => ['required', 'boolean'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        $sanitized['slug'] = str_slug($sanitized['value']);

        // Add your code for manipulation with request data here

        return $sanitized;

    }
}
