<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            // 'discount_id' => $this->discount,
            'discounts' => ['Discount ' =>$this->discount->discount,"description"=>$this->discount->description],
            // 'discription' => $this->discription,
            'services' => ['name'=>$this->service->name, 'description'=>$this->service->description],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
