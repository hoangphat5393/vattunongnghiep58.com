<?php

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class GenerateMenu extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idmenu' => ['required', 'integer', 'exists:menus,id'],
            'menuname' => ['required', 'string', 'max:255'],
            'arraydata' => ['nullable', 'array'],
            'arraydata.*.id' => ['required', 'integer'],
            'arraydata.*.parent' => ['nullable', 'integer'],
            'arraydata.*.sort' => ['nullable', 'integer'],
            'arraydata.*.depth' => ['nullable', 'integer'],
        ];
    }
}
