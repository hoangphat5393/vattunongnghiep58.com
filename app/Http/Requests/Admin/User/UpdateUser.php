<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.user.edit', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $in = implode(",", User::USER_TYPES );
        return [
            'name' => ['nullable', 'string'],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->user->getKey(), $this->user->getKeyName()), 'string'],
            'phone' => ['nullable', Rule::unique('users', 'phone')->ignore($this->user->getKey(), $this->user->getKeyName()), 'string'],
            'email_verified_at' => ['nullable', 'date'],
            'password' => ['nullable', 'min:6', 'string'],
            'user_type' => ['required', 'in:'.$in ],
            'is_vip' => ['required','boolean'],
            'vip_to_date' => ['nullable'],

        ];
    }

    /**
     * Modify input data
     *
     * @return array
     */
    public function getSanitized(): array
    {
        $sanitized = $this->validated();
        if(isset($sanitized['password'])){
            $sanitized['password'] = Hash::make($sanitized['password']);
        }
        //Add your code for manipulation with request data here

        return $sanitized;
    }
}
