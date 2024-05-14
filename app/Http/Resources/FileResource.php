<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            'subject'=>$this->subject,
            'classrom'=>ClassromResource::make(Classrom::findOrFail($this->Classrom_id)),
            'type'=>$this->type,
            'semester'=>$this->semester,
            'path'=>$this->path
        ];
    }
}
