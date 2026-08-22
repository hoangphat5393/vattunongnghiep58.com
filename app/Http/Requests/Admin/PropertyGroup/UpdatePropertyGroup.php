<?php

namespace App\Http\Requests\Admin\PropertyGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePropertyGroup extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.property-group.edit', $this->propertyGroup);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'group_name' => ['required', 'string'],

            'properties' => ['nullable', 'array'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        $sanitized['properties'] = collect($sanitized['properties'])->pluck('id')->toArray();

        // Add your code for manipulation with request data here

        return $sanitized;

    }
}
