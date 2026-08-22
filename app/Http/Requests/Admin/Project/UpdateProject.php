<?php

namespace App\Http\Requests\Admin\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        return Gate::allows('admin.project.edit', $this->project);

    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [

            'title' => ['required', 'string'],

            'slug' => ['required'],

            'excerpt' => ['required', 'string'],

            'description' => ['required', 'string'],

            'gia' => ['required', 'numeric'],

            'gia_m' => ['required', 'numeric'],

            'gia_thue' => ['required', 'numeric'],

            'status' => ['required', 'string'],

            'tong_quan' => ['required', 'array'],

            'thong_tin_chi_tiet' => ['required', 'array'],

            'tien_ich' => ['required', 'array'],

            'city_id' => ['required', 'array'],

            'district_id' => ['required', 'array'],

            'ward_id' => ['required', 'array'],

            'street' => ['required', 'string'],

            'lat' => ['required', 'numeric'],

            'lng' => ['required', 'numeric'],

            'meta_title' => ['nullable', 'string'],

            'meta_description' => ['nullable', 'string'],

            'meta_keyword' => ['nullable', 'string'],

            'category_id' => ['nullable'],

            'investor_id' => ['required', 'array'],

            'published' => ['required', 'boolean'],

            'is_featured' => ['nullable', 'boolean'],

            'video_url' => ['nullable', 'active_url'],

        ];

    }

    /**
     * Modify input data
     */
    public function getSanitized(): array
    {

        $sanitized = $this->validated();

        $sanitized['investor_id'] = $sanitized['investor_id']['id'];

        $sanitized['ward_id'] = $sanitized['ward_id']['id'];

        $sanitized['district_id'] = $sanitized['district_id']['id'];

        $sanitized['city_id'] = $sanitized['city_id']['id'];

        $sanitized['gia'] = $sanitized['gia'];

        // Add your code for manipulation with request data here

        // $temp = [];

        // foreach($sanitized['category_id'] as $item){

        //     $temp[] = [

        //         'id' => $item

        //     ];

        // }

        // $sanitized['category_id'] = $temp;

        return $sanitized;

    }
}
