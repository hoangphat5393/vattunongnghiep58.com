<?php

namespace App\Http\Requests\Admin\UserApp;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateUserApp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.user-app.edit', $this->userApp);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'service_app_id' => ['sometimes', Rule::unique('user_apps', 'service_app_id')->ignore($this->userApp->getKey(), $this->userApp->getKeyName()), 'string'],

            'service_app_name' => ['sometimes', 'string'],

            'flatform' => ['sometimes', 'string'],

            'system_fee' => ['sometimes', 'numeric'],

            'pub_profit_percent' => ['sometimes', 'numeric'],

            'publisher_id' => ['sometimes'],

            'ad_source_id' => ['sometimes'],

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

        if ($sanitized['app_slug'] == '') {

            $sanitized['app_slug'] = str_random(10);

        }

        // Add your code for manipulation with request data here

        return $sanitized;

    }
}
