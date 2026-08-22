<?php

namespace App\Http\Requests\Admin\SalesNews;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StoreSalesNews extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.sales-news.create');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'title' => ['required', 'string'],

            'slug' => ['required', Rule::unique('sales_news', 'slug'), 'string'],

            'url' => ['nullable', 'string'],

            'excerpt' => ['required', 'string'],

            'description' => ['required', 'string'],

            'status' => ['required', 'string'],

            'meta_title' => ['nullable', 'string'],

            'meta_description' => ['nullable', 'string'],

            'meta_keyword' => ['nullable', 'string'],

            'count_view' => ['required', 'integer'],

            'selling_status' => ['required', 'string'],

            'price' => ['required', 'numeric'],

            'unit' => ['required', 'string'],

            'approved_date' => ['nullable', 'date'],

            'start_date' => ['nullable', 'date'],

            'expired_at' => ['nullable', 'date'],

            'sales_news_status' => ['required', 'boolean'],

            'category_id' => ['required', 'string'],

            'package_id' => ['required', 'string'],

            'acreage_id' => ['required', 'string'],

            'bed_id' => ['nullable', 'string'],

            'bath_id' => ['nullable', 'string'],

            'juridical_id' => ['nullable', 'string'],

            'direction_id' => ['nullable', 'string'],

            'floor_id' => ['nullable', 'string'],

            'functional_id' => ['nullable', 'string'],

            'city_id' => ['required', 'string'],

            'district_id' => ['required', 'string'],

            'ward_id' => ['required', 'string'],

            'user_id' => ['required', 'string'],

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
