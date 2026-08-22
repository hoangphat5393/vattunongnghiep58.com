<?php

namespace App\Http\Requests\Admin\PriceConfig;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePriceConfig extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.price-config.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'from' => ['nullable', 'integer'],

            'to' => ['nullable', 'integer'],

            'slug' => ['required', Rule::unique('price_configs', 'slug'), 'string'],

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

        return $sanitized;

    }
}
