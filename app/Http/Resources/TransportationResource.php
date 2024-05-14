<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\School;

class TransportationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return[
            'uuid'=>$this->uuid,
            'number_bus'=>$this->number_bus,
            'school'=>SchoolResource::make(School::findOrFail($this->school_id)),
            'street_name'=>$this->street_name,
            'departure_hour'=>$this->departure_hour
        ];
    }
}