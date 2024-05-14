<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Boss;

class SchoolResource extends JsonResource
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
            'uuid' => $this->uuid,
            'title' => $this->title,
            'address' => $this->address,
            'boss' => BossResource::make(Boss::findOrFail($this->boss_id)),
            'logo' => env('P').$this->logo,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'installment' => $this->installment,
            'transportation_cost' => $this->transportation_cost
        ];
    }
}
