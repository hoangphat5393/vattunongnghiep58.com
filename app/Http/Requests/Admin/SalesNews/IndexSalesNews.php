<?php

namespace App\Http\Requests\Admin\SalesNews;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class IndexSalesNews extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.sales-news.index');

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'orderBy' => 'in:id,title,url,status,meta_title,meta_description,meta_keyword,count_view,selling_status,price,unit,approved_date,start_date,expired_at,sales_news_status,category_id,package_id,acreage_id,bed_id,bath_id,juridical_id,direction_id,floor_id,functional_id,city_id,district_id,ward_id,user_id,sales_new_count|nullable',

            'orderDirection' => 'in:asc,desc|nullable',

            'search' => 'string|nullable',

            'page' => 'integer|nullable',

            'per_page' => 'integer|nullable',

        ];

    }
}
