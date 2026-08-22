<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WardResource extends JsonResource
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

            'district_id' => $this->district_id,

            'id' => $this->id,

            'name' => $this->name,

            'type' => $this->type,

            'slug' => $this->slug,

        ];

    }
}
