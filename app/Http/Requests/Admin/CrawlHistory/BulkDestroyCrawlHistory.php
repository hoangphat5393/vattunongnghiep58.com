<?php

namespace App\Http\Requests\Admin\CrawlHistory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BulkDestroyCrawlHistory extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.crawl-history.bulk-delete');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'ids.*' => 'integer',

        ];

    }
}
