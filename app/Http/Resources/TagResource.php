<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Student;

class TagResource extends JsonResource
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
            'student'=>StudentResource::make(Student::findOrFail($this->student_id)),
            'semester'=>$this->semester,
            'type'=>$this->type,
            'value'=>$this->value,
            'oral'=>$this->oral,
            'total'=>$this->total,
        ];
    }
}
