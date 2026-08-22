<?php

namespace App\Http\Requests\Admin\Website;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreWebsite extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.website.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'uuid' => ['required', 'string'],

            'managed_by_database_connection' => ['nullable', 'string'],

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
