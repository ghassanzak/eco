<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' =>$this->id,
            'product_review_details' =>$this->product_review_details,
            'ip_address' =>$this->ip_address,
            'status' =>$this->status,
            // 'product_id' =>$this->product->id,
            // 'user_id' =>$this->user->id,
        ];
    }
}
