<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePost extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = $this->input('name') ?? $this->input('title');
        if (! $this->filled('slug') && is_string($name) && $name !== '') {
            $this->merge(['slug' => Str::slug($name)]);
        }
    }

    public function rules(): array
    {
        $id = (int) ($this->route('id') ?? $this->input('id'));

        return [
            'id' => ['nullable', 'integer'],
            'name' => ['sometimes', 'string', 'max:500'],
            'title' => ['nullable', 'string', 'max:500'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')
                    ->ignore($id)
                    ->where(fn ($query) => $query->where('type', 'post')),
            ],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'in:0,1'],
            'sort' => ['nullable', 'integer'],
            'seo_title' => ['nullable', 'string', 'max:500'],
            'seo_keyword' => ['nullable', 'string', 'max:500'],
            'seo_description' => ['nullable', 'string'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'string'],
            'submit' => ['nullable', 'string'],
        ];
    }
}
