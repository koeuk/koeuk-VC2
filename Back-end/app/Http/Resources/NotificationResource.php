<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'user_id'=>$this->user,
            'fixer_id'=>$this->fixer,
            'booking_id'=> $this->booking,
            'action' =>$this->action,
            'message' =>$this->message
        ];
    }
}
