<?php

namespace App\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

class StorePermission extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return \Auth::guard('admin')->user()->can('admin.permission.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],

            'slug' => ['required', 'string', 'max:255', 'unique:permissions,slug', 'regex:/(^([0-9A-Za-z\._\-]+)$)/'],

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
