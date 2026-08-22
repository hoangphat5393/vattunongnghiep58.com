<?php

namespace App\Http\Requests\Admin\PriceConfig;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyPriceConfig extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.price-config.delete', $this->priceConfig);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [];

    }
}
