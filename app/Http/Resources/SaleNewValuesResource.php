<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleNewValuesResource extends JsonResource
{
    /**
     * Transform the resource into an array.

     *

     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $field_type = $this->property->field_type;

        return [

            'id' => $this->property->id,

            'field_label' => $this->property->field_label,

            'field_name' => $this->property->field_name,

            'field_type' => $field_type,

            'default_value' => $this->property->default_value,

            'field_value' => $field_type == 'checkbox' ? json_decode($this->value_text) : $this->value_text,

        ];

    }
}
