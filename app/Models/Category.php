<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'number',
        'school_id',
        'classrom_id',
    ];
    protected $casts=[
        'uuid'=>'string',
        'number'=>'integer',
        'school_id'=>'integer',
        'classrom_id'=>'integer',
    ];

    public function school(){
    return $this->belongsTo(School::class);
    }
    public function classrom(){
    return $this->belongsTo(Classrom::class);
    }
    public function students(){
    return $this->hasMany(Student::class);
    }
    public function teachers(){
    return $this->hasMany(Teacher::class);
    }
}
