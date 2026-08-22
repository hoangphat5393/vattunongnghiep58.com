<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.

     *

     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [

            'id' => $this->id,

            'field_label' => $this->field_label,

            'field_name' => $this->field_name,

            'field_type' => $this->field_type,

            'default_value' => $this->default_value,

            'field_value' => '',

        ];

    }
}
