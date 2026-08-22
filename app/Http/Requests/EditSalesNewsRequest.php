<?php

namespace App\Http\Requests;

use App\Models\SalesNews;
use Illuminate\Foundation\Http\FormRequest;

class EditSalesNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.

     *

     * @return bool
     */
    protected $post;

    public function authorize()
    {

        $this->post = SalesNews::find(request()->route('id'));

        return $this->post && $this->post->user_id == auth()->id();

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

    public function getData()
    {

        return $this->post;

    }
}
