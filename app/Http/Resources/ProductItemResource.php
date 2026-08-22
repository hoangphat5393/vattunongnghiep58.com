<?php

namespace App\Http\Resources;

use App\Models\SalesNews;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductItemResource extends JsonResource
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

            'href' => route('front.sales_news.detail', ['slug' => $this->slug, 'id' => $this->id]),

            'thumbnail' => $this->thumbnail,

            'type' => $this->getNewsType(),

            'user_type' => $this->getUserType(),

            'price' => $this->getPricing(),

            'price_text' => $this->getPricing(),

            'address' => $this->getFullAddress(),

            'created_at' => $this->getCreatedTime(),

            'lat' => $this->lat,

            'lng' => $this->lng,

            'star' => $this->star,

            'square' => $this->square,

            'status_text' => SalesNews::SELLING_STATUS[$this->selling_status],

            'square_text' => number_format($this->square),

        ];

    }
}
