<?php

namespace App\Http\Requests\Admin\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateCity extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.city.edit', $this->city);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'name' => ['sometimes', 'string'],

            'type' => ['sometimes', 'string'],

            'slug' => ['sometimes', Rule::unique('cities', 'slug')->ignore($this->city->getKey(), $this->city->getKeyName()), 'string'],

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
