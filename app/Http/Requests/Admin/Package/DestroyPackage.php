<?php

namespace App\Http\Requests\Admin\Package;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyPackage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.package.delete', $this->package);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [];

    }
}
