<?php

namespace App\Http\Requests\Admin\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyProperty extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.property.delete', $this->property);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [];

    }
}
