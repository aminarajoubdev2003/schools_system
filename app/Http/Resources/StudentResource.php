<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\School;
use App\Models\Classrom;
use App\Models\Transportation;

class StudentResource extends JsonResource
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
            'name'=>$this->name,
            'father_name'=>$this->father_name,
            'image'=>env('P').$this->image,
            'born'=>$this->born,
            'age'=>Carbon::createFromDate($this->born)->age."عام",
            'school'=>SchoolResource::make(School::findOrFail($this->school_id)),
            'classrom'=>ClassromResource::make(Classrom::findOrFail($this->Classrom_id)),
            'number'=>$this->number,
            'transportation'=>TransportationResource::make(Transportation::findOrFail($this->transportation_id)),
            'average'=>$this->average
        ];
    }
}
