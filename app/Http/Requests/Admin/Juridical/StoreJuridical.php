<?php

namespace App\Http\Requests\Admin\Juridical;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreJuridical extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.juridical.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'value' => ['required', 'string'],

            // 'slug' => ['required', Rule::unique('juridicals', 'slug'), 'string'],

            'status' => ['required', 'boolean'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        // Add your code for manipulation with request data here

        $sanitized['slug'] = str_slug($sanitized['value']);

        return $sanitized;

    }
}
