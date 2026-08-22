<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class SaveLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.

     *

     * @return bool
     */
    public function authorize()
    {

        return true;

    }

    /**
     * Get the validation rules that apply to the request.

     *

     * @return array
     */
    public function rules()
    {

        return [

            'name' => 'required|max:254',

            'phone' => [

                'required',

                new PhoneNumber,

            ],

            'product_id' => 'required|exists:products,id',

            'note' => 'nullable',

        ];

    }

    public function getSanitized()
    {

        $data = $this->validated();

        return $data;

    }
}
