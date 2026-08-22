<?php

namespace App\Http\Requests\Admin\Acreage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateAcreage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.acreage.edit', $this->acreage);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'from' => ['nullable', 'integer'],

            'to' => ['nullable', 'integer'],

            'slug' => ['sometimes', Rule::unique('acreages', 'slug')->ignore($this->acreage->getKey(), $this->acreage->getKeyName()), 'string'],

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
