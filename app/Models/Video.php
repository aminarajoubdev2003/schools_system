<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'school_id',
        'url',
        'desc',
    ];
    protected $casts=[
        'uuid'=>'string',
        'school_id'=>'integer',
        'url'=>'string',
        'desc'=>'string',
    ];

    public function school(){
    return $this->belongsTo(School::class);
    }
    
}
