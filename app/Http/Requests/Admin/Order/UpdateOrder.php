<?php

namespace App\Http\Requests\Admin\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('admin.order.edit', $this->order);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer' => ['required'],
            'items' => ['required','array'],
            'customer_id' => ['required'],
            'user_id' => ['nullable'],
            'note' => ['nullable', 'string'],
            'action' => [ 'required', 'string'],
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
        $sanitized['customer_id'] = $sanitized['customer']['id'];
        //Add your code for manipulation with request data here
        return $sanitized;
    }
}
