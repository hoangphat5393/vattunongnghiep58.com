<?php

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItem extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'labelmenu' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'slugmenu' => ['nullable', 'string', 'max:255'],
            'linkmenu' => ['nullable', 'string', 'max:1000'],
            'targetmenu' => ['nullable', 'string', 'max:50'],
            'relmenu' => ['nullable', 'string', 'max:100'],
        ];
    }
}
