<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class SaveContactRequest extends FormRequest
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

            'name' => 'nullable|max:254',

            'email' => 'nullable|email|max:254',

            'phone' => [

                'nullable',

                new PhoneNumber,

            ],

            'subject' => 'nullable|max:254',

            'message' => 'nullable',

            'contact_type' => 'required',

        ];

    }

    public function getData()
    {

        $data = $this->validated();

        return $data;

    }
}
