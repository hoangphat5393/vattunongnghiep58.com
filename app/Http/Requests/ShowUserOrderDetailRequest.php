<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class ShowUserOrderDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.

     *

     * @return bool
     */
    public function authorize()
    {

        $order = Order::find(request()->route('id'));

        return $order->customer_id == auth()->id();

    }

    /**
     * Get the validation rules that apply to the request.

     *

     * @return array
     */
    public function rules()
    {

        return [

            //

        ];

    }
}
