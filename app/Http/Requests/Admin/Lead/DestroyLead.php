<?php

namespace App\Http\Requests\Admin\Lead;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyLead extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.lead.delete', $this->lead);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [];

    }
}
