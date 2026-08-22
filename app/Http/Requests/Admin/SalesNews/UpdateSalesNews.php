<?php

namespace App\Http\Requests\Admin\SalesNews;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateSalesNews extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.sales-news.edit', $this->salesNews);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'title' => ['sometimes', 'string'],

            'slug' => ['sometimes', Rule::unique('sales_news', 'slug')->ignore($this->salesNews->getKey(), $this->salesNews->getKeyName()), 'string'],

            'url' => ['nullable', 'string'],

            'excerpt' => ['sometimes', 'string'],

            'description' => ['sometimes', 'string'],

            'status' => ['sometimes', 'string'],

            'meta_title' => ['nullable', 'string'],

            'meta_description' => ['nullable', 'string'],

            'meta_keyword' => ['nullable', 'string'],

            'count_view' => ['sometimes', 'integer'],

            'selling_status' => ['sometimes', 'string'],

            'price' => ['sometimes', 'numeric'],

            'unit' => ['sometimes', 'string'],

            'approved_date' => ['nullable', 'date'],

            'start_date' => ['nullable', 'date'],

            'expired_at' => ['nullable', 'date'],

            'sales_news_status' => ['sometimes', 'boolean'],

            'category_id' => ['sometimes'],

            'package_id' => ['sometimes'],

            'acreage_id' => ['sometimes'],

            'bed' => ['nullable'],

            'bath' => ['nullable'],

            'juridical' => ['nullable'],

            'direction' => ['nullable'],

            'floor' => ['nullable'],

            'functional' => ['nullable'],

            'city_id' => ['sometimes'],

            'district_id' => ['sometimes'],

            'ward_id' => ['sometimes'],

            'user_id' => ['sometimes'],

            'video_url' => ['nullable', 'active_url'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        // Add your code for manipulation with request data here

        return $sanitized;

    }
}
