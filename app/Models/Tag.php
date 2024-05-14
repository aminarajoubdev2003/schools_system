<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'subject',
        'student_id',
        'semester',
        'type',
        'value',
        'oral',
        'total',
    ];
    protected $casts=[
        'uuid'=>'string',
        'subject'=>'string',
        'student_id'=>'integer',
        'semester'=>'string',
        'type'=>'string',
        'value'=>'integer',
        'oral'=>'integer',
        'total'=>'integer',
    ];
    public function student(){
    return $this->belongsTo(Student::class);
    }
}
