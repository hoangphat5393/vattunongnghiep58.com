<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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

            'title' => $this->title,

            'video_url' => $this->video_url,

            'front_url' => $this->front_url,

            'thumbnail' => $this->thumbnail,

            'status_text' => $this->status_text,

            'slug' => $this->slug,

            'excerpt' => $this->excerpt,

            'description' => $this->excerpt,

            'gia' => $this->gia,

            'gia_text' => $this->getPricingText(),

            'gia_m' => $this->gia_m,

            'gia_thue' => $this->gia_thue,

            'status' => $this->status,

            'tong_quan' => $this->tong_quan,

            'thong_tin_chi_tiet' => $this->thong_tin_chi_tiet,

            'tien_ich' => $this->tien_ich,

            'street' => $this->street,

            'lat' => $this->lat,

            'lng' => $this->lng,

            'meta_title' => $this->meta_title,

            'meta_description' => $this->meta_description,

            'meta_keyword' => $this->meta_keyword,

            'category_id' => $this->category_id,

            'investor_id' => new InvestorResource($this->investor),

            'city_id' => new CityResource($this->city),

            'district_id' => new DistrictResource($this->district),

            'ward_id' => new WardResource($this->ward),

            'published' => $this->published,

            'is_featured' => $this->is_featured,

            'address' => $this->street.', '.$this->ward->name.', '.$this->district->name.', '.$this->city->name,

        ];

    }
}
