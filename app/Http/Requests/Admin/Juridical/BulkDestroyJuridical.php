<?php

namespace App\Http\Requests\Admin\Juridical;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BulkDestroyJuridical extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.juridical.bulk-delete');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'ids.*' => 'integer',

        ];

    }
}
