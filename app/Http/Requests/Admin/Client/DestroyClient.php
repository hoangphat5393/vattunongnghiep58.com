<?php

namespace App\Http\Requests\Admin\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DestroyClient extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.client.delete', $this->client);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [];

    }
}
