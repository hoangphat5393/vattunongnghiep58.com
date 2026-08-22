<?php

namespace App\Http\Requests\Admin\UserApp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreUserApp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.user-app.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'service_app_id' => ['required', Rule::unique('user_apps', 'service_app_id'), 'string'],

            'service_app_name' => ['required', 'string'],

            'flatform' => ['required', 'string'],

            'system_fee' => ['required', 'numeric'],

            'pub_profit_percent' => ['required', 'numeric'],

            'publisher_id' => ['required', 'string'],

            'ad_source_id' => ['required', 'string'],

            'system_fee_type' => ['nullable', 'string'],

            'has_vat' => ['required'],

            'app_slug' => ['nullable'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        // Add your code for manipulation with request data here

        if ($sanitized['app_slug'] == '') {

            $sanitized['app_slug'] = str_random(10);

        }

        return $sanitized;

    }
}
