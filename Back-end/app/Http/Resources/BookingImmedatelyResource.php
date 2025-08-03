<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingImmedatelyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user_id,
            'promotion' => $this->promotion_id,
            'date' => $this->date,
            'latitude' => $this->latitude,
            'start'=>$this->created_at,
            'longitude' => $this->longitude,
            'message' => $this->message,
            'service' => $this->service ? $this->service->name : null,
        ];
    }
}
