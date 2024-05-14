<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\School;
use App\Models\Classrom;

class CategoryResource extends JsonResource
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
            'school'=>SchoolResource::make(School::findOrFail($this->school_id)),
            'classrom'=>ClassromResource::make(Classrom::findOrFail($this->classrom_id)),
            'number'=>$this->number
        ];
    }
}
