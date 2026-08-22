<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectPaginateResource extends JsonResource
{
    protected $projects;

    public function __construct($projects)
    {

        $this->projects = $projects;

    }

    /**
     * Transform the resource into an array.

     *

     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [

            'data' => ProjectResource::collection($this->projects->items()),

            'total' => $this->projects->total(),

            'count' => $this->projects->count(),

            'currentPage' => $this->projects->currentPage(),

            'hasMorePages' => $this->projects->hasMorePages(),

            'perPage' => $this->projects->perPage(),

            'totalPages' => $this->projects->lastPage(),

        ];

    }
}
