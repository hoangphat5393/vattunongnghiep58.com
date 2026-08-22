<?php

namespace App\Http\Requests;

use App\Models\SalesNews;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSalesNewsRequest extends FormRequest
{
    public function authorize()
    {

        $sale = SalesNews::find($this->route('id'));

        return $sale && auth()->id() == $sale->user_id;

    }

    public function validationData()
    {

        $data = $this->all();

        $data['params'] = json_decode($data['params'], true);

        return $data;

    }

    /**
     * Get the validation rules that apply to the request.

     *

     * @return array
     */
    public function rules()
    {

        return [

            'params.title' => 'required',

            'params.price' => 'required',

            'params.category_id' => 'required|exists:categories,id',

            'params.city_id' => 'required|exists:cities,id',

            'params.district_id' => 'required|exists:districts,id',

            'params.ward_id' => 'required|exists:wards,id',

            'params.description' => 'required',

            'params.project_id' => 'nullable|exists:projects,id',

            'params.square' => 'required', // diện tích

            'params.direction' => 'required', // hướng

            'params.juridical' => 'required', //  pháp lý

            'params.width' => 'nullable|integer',

            'params.height' => 'nullable|integer',

            'params.street' => 'required',

            'params.lat' => 'required|numeric',

            'params.lng' => 'required|numeric',

            'params.fields' => 'nullable|array',

            'params.news_type' => 'required',

            'params.user_type' => 'required',

            'params.contact_name' => 'required|max:200',

            'params.contact_email' => 'nullable|email|max:200',

            'params.contact_phone' => ['required', new PhoneNumber],

            'files' => 'nullable|array',

            'params.video_url' => ['nullable', 'active_url'],

            'params.selling_status' => ['required'],

        ];

    }

    public function getData()
    {

        $data = $this->validated();

        $data['params']['price'] = $data['params']['price'] * 1000000;

        return $data;

    }

    public function messages()
    {

        return [

            'params.title.required' => 'Trường này là bắt buộc',

            'params.price.required' => 'Trường này là bắt buộc',

            'params.category_id.required' => 'Trường này là bắt buộc',

            'params.category.required' => 'Trường này là bắt buộc',

            'params.street.required' => 'Trường này là bắt buộc',

            'params.direction.required' => 'Trường này là bắt buộc',

            'params.city_id.required' => 'Trường này là bắt buộc',

            'params.district_id.required' => 'Trường này là bắt buộc',

            'params.ward_id.required' => 'Trường này là bắt buộc',

            'params.description.required' => 'Trường này là bắt buộc',

            'params.acreage.required' => 'Trường này là bắt buộc',

            'params.user_type.required' => 'Trường này là bắt buộc',

            'params.news_type.required' => 'Trường này là bắt buộc',

            'params.width.required' => 'Trường này là bắt buộc',

            'params.height.required' => 'Trường này là bắt buộc',

            'files.required' => 'Vui lòng chọn ảnh',

        ];

    }
}
