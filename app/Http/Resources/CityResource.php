<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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

            'slug' => $this->slug,

            'name' => $this->name,

            'type' => $this->type,

            'lat' => $this->lat,

            'lng' => $this->lng,

        ];

    }
}
