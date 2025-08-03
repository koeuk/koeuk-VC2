<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingShow extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $bookings = [];

        // Check for immediately bookings
        if ($this->type === 'immediately' && ($this->action === 'request' || $this->action === 'progress')) {
            $bookingResource = new BookingImmedatelyResource($this->booking_immedately);
            $bookings[] = [
                'id' => $this->id,
                'booking' => $bookingResource->toArray($request),
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    // Add more user attributes as needed
                ],
                'date' => $this->booking_immedately->date,
                'type' => $this->type,
                'action' => $this->action,
                'fixer'=>$this->fixer

            ];
        }

        // Check for deadline bookings
        elseif ($this->type === 'deadline' && ($this->action === 'request' || $this->action === 'progress')) {
            $bookingResource = new BookingDeadlineResource($this->booking_deadline);
            $bookings[] = [
                'id' => $this->id,
                'booking' => $bookingResource->toArray($request),
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    // Add more user attributes as needed
                ],
                'date' => $this->booking_deadline->date,
                'type' => $this->type,
                'action' => $this->action,
                'fixer'=>$this->fixer
            ];
        }

        return  $bookings;
    }
}
