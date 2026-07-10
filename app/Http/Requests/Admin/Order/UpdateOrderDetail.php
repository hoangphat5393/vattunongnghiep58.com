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
            'cart_id' => ['required', 'integer', 'exists:shop_orders,cart_id'],
            'admin_note' => ['nullable', 'string'],
            'cart_status' => ['required', 'integer', 'in:0,1,2,3'],
            'cart_payment' => ['nullable', 'integer', 'in:0,1'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
