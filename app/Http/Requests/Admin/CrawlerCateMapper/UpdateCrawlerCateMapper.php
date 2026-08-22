<?php

namespace App\Http\Requests\Admin\CrawlerCateMapper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCrawlerCateMapper extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.crawler-cate-mapper.edit', $this->crawlerCateMapper);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'category_id' => ['required', 'integer'],

            'district_id' => ['required'],

            'city_id' => ['required'],

            'ward_id' => ['nullable'],

            'crawl_url' => ['required', 'string'],

            'activated' => ['required', 'boolean'],

            'is_first_time_crawled' => ['required', 'boolean'],

            'count_crawl' => ['required'],

            'page_first_crawl' => ['required'],

            'page_second_crawl' => ['required'],

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
