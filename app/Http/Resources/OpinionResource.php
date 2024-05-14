<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\School;

class OpinionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       // return parent::toArray($request);
       return[
        'uuid'=>$this->uuid,
        'name'=>$this->name,
        'school'=>SchoolResource::make(School::findOrFail($this->school_id)),
        'comment'=>$this->comment,
        'rate'=>$this->rate
       ];
    }
}
