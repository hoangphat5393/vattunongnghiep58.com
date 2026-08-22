<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Models\SalesNews;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleNewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.

     *

     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $project = Project::where('id', $this->project_id)

            ->select(

                'id',

                'title',

                'slug',

                'city_id',

                'district_id',

                'ward_id',

                'status',

                'street',

                'lat',

                'lng'

            )

            ->first();

        return [

            'id' => $this->id,

            'square' => $this->square,

            'selling_status' => $this->selling_status,

            'selling_status_text' => isset(SalesNews::SELLING_STATUS[$this->selling_status])

                ? SalesNews::SELLING_STATUS[$this->selling_status]

                : '',

            'square_text' => number_format($this->square),

            'approved_date' => $this->approved_date,

            'description' => $this->description,

            'direction' => $this->direction,

            'category_id' => new CategoryResource($this->categories),

            'city_id' => new CityResource($this->city),

            'district_id' => new DistrictResource($this->district),

            'ward_id' => new WardResource($this->ward),

            'juridical' => $this->juridical,

            'package_id' => $this->package_id,

            'price' => $this->price / 1000000,

            'price_text' => $this->getPricing(),

            'street' => $this->street,

            'title' => $this->title,

            'user_type' => $this->user_type,

            'news_type' => $this->news_type,

            'width' => $this->width,

            'height' => $this->height,

            'lat' => $this->lat,

            'lng' => $this->lng,

            'contact_name' => $this->contact_name,

            'contact_phone' => $this->contact_phone,

            'contact_email' => $this->contact_email,

            'fields' => SaleNewValuesResource::collection($this->fields),

            // 'category' => new CategoryResource($this->category),

            'project_id' => $project,

            'files' => MediaResource::collection($this->media),

        ];

    }
}
