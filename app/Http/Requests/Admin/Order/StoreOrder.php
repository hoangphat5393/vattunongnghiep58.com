<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.order.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            // 'customer_id' => ['required'],

            'customer' => ['required'],

            'product_name' => ['required', 'string'],

            'start_date' => ['required', 'date'],

            'end_date' => ['required', 'date'],

            'price' => ['required', 'integer'],

            'qty' => ['required', 'integer'],

            'total' => ['required', 'integer'],

            'status' => ['required', 'string'],

            'note' => ['nullable', 'string'],

            'product_type' => ['required', 'string'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        // Add your code for manipulation with request data here

        $sanitized['customer_id'] = $sanitized['customer']['id'];

        return $sanitized;

    }
}
