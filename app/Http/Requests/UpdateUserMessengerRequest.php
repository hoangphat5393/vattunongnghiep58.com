<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserMessengerRequest extends FormRequest
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

            'fb_messenger' => ['nullable'],

            'phone_zalo' => ['nullable', new PhoneNumber],

        ];

    }

    public function getData()
    {

        return $this->validated();

    }
}
