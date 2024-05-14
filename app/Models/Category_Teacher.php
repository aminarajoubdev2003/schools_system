<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category_Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'category_id',
        'teacher_id',
    ];
    protected $casts=[
        'uuid'=>'string',
        'category_id'=>'integer',
        'teacher_id'=>'integer',
    ];
}
