<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'school_id',
        'student_id',
        'type',
        'amount',
    ];
    protected $casts=[
        'uuid'=>'string',
        'school_id'=>'integer',
        'student_id'=>'integer',
        'type'=>'string',
        'amount'=>'integer',
    ];

    public function school(){
    return $this->belongsTo(School::class);
    }
    public function student(){
    return $this->belongsTo(Student::class);
    }
}
