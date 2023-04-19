<?php

namespace App\Resources;

use App\Models\Ticket;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        if($this->status == Ticket::STATUS_OPEN){
            $status = "Open";
        } else {
            $status = "Closed";
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
            'status' => $status,
            'reference_id' => $this->reference_id,
            'phone_number' => $this->phone_number,
        ];
    }
}