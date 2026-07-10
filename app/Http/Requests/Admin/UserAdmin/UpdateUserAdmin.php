<?php

namespace App\Http\Requests\Admin\UserAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserAdmin extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = (int) ($this->route('id') ?? $this->input('id'));

        return [
            'id' => ['nullable', 'integer'],
            'fullname' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($id),
            ],
            'birthday' => ['nullable', 'string', 'max:50'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'email_info' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:0,1'],
            'image' => ['nullable', 'string', 'max:500'],
            'province' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'ward' => ['nullable', 'string', 'max:255'],
            'check_pass' => ['nullable'],
            'password' => ['nullable', 'required_with:check_pass', 'string', 'min:6', 'max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer'],
            'submit' => ['nullable', 'string'],
        ];
    }
}
