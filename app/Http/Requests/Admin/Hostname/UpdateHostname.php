<?php

namespace App\Http\Requests\Admin\Hostname;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateHostname extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.hostname.edit', $this->hostname);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'fqdn' => ['sometimes', Rule::unique('hostnames', 'fqdn')->ignore($this->hostname->getKey(), $this->hostname->getKeyName()), 'string'],

            'redirect_to' => ['nullable', 'string'],

            'force_https' => ['sometimes', 'boolean'],

            'under_maintenance_since' => ['nullable', 'date'],

            'website_id' => ['nullable', 'string'],

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
