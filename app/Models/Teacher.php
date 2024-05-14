<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'start_year',
        'image',
        'certificates',
        'salary',
        'school_id',
    ];
    protected $casts=[
        'uuid'=>'string',
        'name'=>'string',
        'start_year'=>'integer',
        'image'=>'string',
        'certificates'=>'json',
        'salary'=>'integer',
        'school_id'=>'integer',
    ];

    public function school(){
    return $this->belongsTo(School::class);
    }
    public function categories(){
    return $this->hasMany(Category::class);
    }
}
