<?php

namespace App\Http\Requests\Admin\CrawlHistory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCrawlHistory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.crawl-history.edit', $this->crawlHistory);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'site' => ['sometimes', 'string'],

            'category_id' => ['sometimes', 'integer'],

            'district_id' => ['sometimes', 'integer'],

            'url' => ['sometimes', 'string'],

            'is_crawled' => ['sometimes', 'boolean'],

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
