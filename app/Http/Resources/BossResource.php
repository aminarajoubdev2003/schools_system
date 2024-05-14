<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BossResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'uuid'=>$this->uuid,
            'name'=>$this->name,
            'start_year'=>$this->start_year,
           // 'image'=>env('P').$this->image,
           'image'=>$this->image,
        ];
    }
}
