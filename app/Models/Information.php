<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'school_id',
        'title',
        'content',
        'image',
    ];
    protected $casts=[
        'uuid'=>'string',
        'school_id'=>'integer',
        'title'=>'string',
        'content'=>'string',
        'image'=>'string',
    ];
    public function school(){
    return $this->belongsTo(School::class);
    }

}
