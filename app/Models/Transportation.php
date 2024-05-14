<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'number_bus',
        'school_id',
        'street_name',
        'departure_hour',
    ];
    protected $casts=[
        'uuid'=>'string',
        'number_bus'=>'integer',
        'school_id'=>'integer',
        'street_name'=>'string',
        'departure_hour'=>'datetime',
    ];

    public function school(){
    return $this->belongsTo(School::class);
    }
    public function students(){
    return $this->hasMany(Student::class);
    }

}
