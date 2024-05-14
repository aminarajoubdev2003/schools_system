<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;
use App\Models\Teacher;

class CategoryTeacherResource extends JsonResource
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
            'category'=>CategoryResource::make(Category::findOrFail($this->category_id)),
            'teacher'=>TeacherResource::make(Teacher::findOrFail($this->teacher_id))
        ];
    }
}
