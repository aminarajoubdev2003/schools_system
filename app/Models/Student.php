<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'father_name',
        'born',
        'school_id',
        'category_id',
        'number',
        'transportation_id',
        'average'
    ];
    protected $casts=[
        'uuid'=>'string',
        'name'=>'string',
        'father_name'=>'string',
        'born'=>'date',
        'school_id'=>'integer',
        'category_id'=>'integer',
        'number'=>'integer',
        'transportation_id'=>'integer',
        'average'=>'integer'
    ];

    public function school(){
    return $this->belongsTo(School::class);
    }
    public function category(){
    return $this->belongsTo(Category::class);
    }
    public function transportation(){
    return $this->belongsTo(Transportation::class);
    }
    public function tags(){
    return $this->hasMany(Tag::class);
    }
    public function payments(){
    return $this->hasMany(Payment::class);
    }
}
