<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'uuid' => $this->uuid,
            'unit' => $this->unit,
            'description' => $this->description,
            'pac' =>$this->pac,
            'unit_value' => $this->unit_value,
            'po_number' => $this->po_number,
            "date_acquired" => $this->date_acquired,
            'location' => $this->location ? $this->location->location : null,
            'condition' => $this->condition_number->condition_number . ' ' . $this->condition->condition,
            'qr_code' => $this->qrCode ? $this->qrCode->qr_code_data : null,
            'image_path' => asset('storage/' . $this->image_path) ?? null,
            'qr_code_image' => $this->qrCode ? asset('storage/' . $this->qrCode->image_path)  : null
        ];
    }
}