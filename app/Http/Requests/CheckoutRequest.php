<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'order.name' => 'required|string|max:200',
            'order.email' => 'required|email|max:300',
            'order.phone' => 'required|string|max:50',
            'order.address' => 'required|string',
            'order.content' => 'nullable|string',
        ];

        if (filled(config('recaptchav3.secret'))) {
            $rules['g-recaptcha-response'] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'order.name.required' => 'Vui lòng nhập họ tên',
            'order.email.required' => 'Vui lòng nhập email',
            'order.email.email' => 'Email không đúng định dạng',
            'order.phone.required' => 'Vui lòng nhập số điện thoại',
            'order.address.required' => 'Vui lòng nhập địa chỉ',
            'g-recaptcha-response.required' => 'Vui lòng xác minh recaptcha',
        ];
    }
}
