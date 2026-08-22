<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
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

            'created_at' => $this->created_at->format('Y-m-d'),

            'username' => $this->username,

            'id' => $this->id,

            'roles' => $this->roles->pluck('name'),

            // 'roles' => $this->roles->map( function( $role ){

            //     return [

            //         'name' => $role->name

            //     ];

            // })

        ];

    }
}
