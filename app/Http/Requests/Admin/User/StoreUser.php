<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.user.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        $in = implode(',', App\Models\User::USER_TYPES);

        return [

            'name' => ['required', 'string'],

            'user_type' => ['required', 'in:'.$in],

            'email' => ['required', 'email', Rule::unique('users', 'email'), 'string'],

            'phone' => ['required', Rule::unique('users', 'phone'), 'string'],

            'email_verified_at' => ['nullable', 'date'],

            'password' => [

                'required',

                // 'confirmed',

                // 'min:7',

                // 'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/',

                // 'string'

            ],

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
