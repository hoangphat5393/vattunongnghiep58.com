<?php

namespace App\Http\Requests\Admin\Direction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateDirection extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.direction.edit', $this->direction);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'value' => ['sometimes', 'string'],

            'slug' => ['sometimes', Rule::unique('directions', 'slug')->ignore($this->direction->getKey(), $this->direction->getKeyName()), 'string'],

            'status' => ['sometimes', 'boolean'],

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
