<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSetting extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.setting.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'key' => ['required', 'string'],

            'value' => ['required', 'string'],

            'field_type' => ['required', 'string'],

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
