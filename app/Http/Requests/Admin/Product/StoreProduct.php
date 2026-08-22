<?php

namespace App\Http\Requests\Admin\Product;

use App\Models\Backend\Product as ProductModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreProduct extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('admin.product.create');
    }

    protected function prepareForValidation(): void
    {
        $name = $this->input('name');
        if (! $this->filled('slug') && is_string($name) && $name !== '') {
            $this->merge(['slug' => Str::slug($name)]);
        }
    }

    public function rules(): array
    {
        $table = (new ProductModel)->getTable();

        return [
            'name' => ['required', 'string', 'max:500'],
            'slug' => ['required', 'string', 'max:255', Rule::unique($table, 'slug')],
            'description' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'status' => ['nullable', 'in:0,1'],
            'category_id' => ['nullable', 'array'],
            'category_id.*' => ['integer'],
            'price' => ['nullable'],
            'sale_price' => ['nullable'],
            'price_type' => ['nullable', 'string', 'in:price,contact'],
            'unit' => ['nullable', 'string', 'max:100'],
            'stock' => ['nullable'],
            'sort' => ['nullable'],
            'prices' => ['nullable', 'array'],
            'prices.*.label' => ['nullable', 'string', 'max:255'],
            'prices.*.price' => ['nullable'],
            'prices.*.unit' => ['nullable', 'string', 'max:100'],
            'prices.*.status' => ['nullable', 'in:0,1'],
            'prices_default' => ['nullable'],
            'seo_title' => ['nullable', 'string'],
            'seo_keyword' => ['nullable', 'string'],
            'seo_description' => ['nullable', 'string'],
        ];
    }

    public function getSanitized(): array
    {
        return $this->validated();
    }
}
