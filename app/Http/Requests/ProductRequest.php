<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.

     *

     * @return bool
     */
    public function authorize()
    {

        return auth()->check();

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

            'params.province_id' => 'required|exists:cities,id',

            'params.district_id' => 'required|exists:districts,id',

            'params.ward_id' => 'required|exists:wards,id',

            'params.description' => 'required',

            'params.project_id' => 'nullable|exists:projects,id',

            'params.acreage' => 'required', // diện tích

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

            'files' => 'required|array',

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

            'params.lat.required' => 'Chọn địa điểm kinh độ trên bản đồ',

            'params.lng.required' => 'Chọn địa điểm vĩ độ trên bản đồ',

            'files.required' => 'Vui lòng chọn ảnh',

        ];

    }
}
