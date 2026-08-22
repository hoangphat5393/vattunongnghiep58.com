<?php

namespace App\Http\Requests\Admin\AlbumItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbumItem extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
        ];
    }

    public function getSanitized(): array
    {
        return $this->validated();
    }
}
