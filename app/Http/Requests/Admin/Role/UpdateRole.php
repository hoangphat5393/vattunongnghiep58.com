<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRole extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Auth::guard('admin')->user()->can('admin.role.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $id = $this->route('id') ?? $this->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($id)],
            'slug' => ['required', 'string', 'max:255', Rule::unique('roles', 'slug')->ignore($id), 'regex:/(^([0-9A-Za-z\._\-]+)$)/'],
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
