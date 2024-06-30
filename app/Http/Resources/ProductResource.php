<?php

namespace App\Http\Resources;

use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $reviewResource = ReviewResource::collection($this->reviews);
        $reviewCount = count($reviewResource);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' =>  $this->slug,
            'category' => new CategoryResource($this->category),
            'images' => ProductImageResource::collection($this->images_product),
            'code' => $this->code,
            'brand' => $this->brand,
            'current_purchase_cost' => $this->current_purchase_cost ,
            'current_sale_price' =>  $this->current_sale_price,
            'available_quantity' =>  $this->available_quantity,
            'description' => $this->description ,
            'is_popular' =>  $this->is_popular,
            'is_trending' => $this->is_trending,
            'status' =>  $this->status(),
            'tags' =>  TagResource::collection($this->tags),
            'reviews' => ReviewResource::collection($this->reviews),
            'reviews_count' => $reviewCount,
        ];
    }
}