<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StorePost extends FormRequest
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
        return [
            'name' => ['required_without:title', 'nullable', 'string', 'max:500'],
            'title' => ['required_without:name', 'nullable', 'string', 'max:500'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->where(fn ($query) => $query->where('type', 'post')),
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
