<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\SalesNews;
use App\Models\City;

class ProductResource extends JsonResource
{

    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $title = '';
        $city = City::where('slug', $request->get('city_slug'))->first();
        $city ?  $title .= ' ' . $city->name  : '';

        return [
            'data' => ProductItemResource::collection($this->products->items()),
            'total' => $this->products->total(),
            'count' => $this->products->count(),
            'currentPage' => $this->products->currentPage(),
            'hasMorePages' => $this->products->hasMorePages(),
            'perPage' => $this->products->perPage(),
            'title' => $title,
            'totalPages' => $this->products->lastPage(),
        ];
    }
}
