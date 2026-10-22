<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderDetail extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required_without:cart_id', 'nullable', 'integer', 'exists:orders,id'],
            'cart_id' => ['required_without:id', 'nullable', 'integer', 'exists:orders,id'],
            'admin_note' => ['nullable', 'string'],
            'status' => ['nullable', 'integer', 'in:0,1,2,3'],
            'cart_status' => ['nullable', 'integer', 'in:0,1,2,3'],
            'payment_status' => ['nullable', 'integer', 'in:0,1'],
            'cart_payment' => ['nullable', 'integer', 'in:0,1'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
