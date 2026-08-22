<?php

namespace App\Http\Requests\Admin\CrawlerSite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCrawlerSite extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.crawler-site.edit', $this->crawlerSite);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'site_name' => ['sometimes', 'string'],

            'site_url' => ['sometimes', 'string'],

            'crawl_time_delay' => ['sometimes', 'integer'],

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
