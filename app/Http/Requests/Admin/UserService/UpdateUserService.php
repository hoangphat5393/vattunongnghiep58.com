<?php

namespace App\Http\Requests\Admin\UserService;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateUserService extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.user-service.edit', $this->userService);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'customer' => ['required', 'array'],

            'product' => ['required', 'array'],

            // 'customer_id' => ['nullable', 'array'],

            // 'product_id' => ['nullable', 'array'],

            'service_name' => ['required', 'string'],

            'service_info' => ['required', 'string'],

            'service_detail' => ['nullable', 'string'],

            'start_date' => ['required', 'date'],

            'end_date' => ['required', 'date'],

            'num_month' => ['required', 'integer'],

            'price' => ['required', 'integer'],

            'quantity' => ['required', 'integer'],

            'subtotal' => ['required', 'integer'],

            'status' => ['required', 'string'],

            'product_type' => ['required', 'string'],

            'note' => ['nullable', 'string'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        $sanitized['customer_id'] = $sanitized['customer']['id'];

        $sanitized['product_id'] = $sanitized['product']['id'];

        // $sanitized['service_info'] = ($sanitized['service_info']);

        // Add your code for manipulation with request data here

        return $sanitized;

    }
}
