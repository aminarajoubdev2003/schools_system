<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opinion extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'school_id',
        'comment',
        'rate',
    ];
    protected $casts=[
        'uuid'=>'string',
        'name'=>'string',
        'school_id'=>'integer',
        'comment'=>'string',
        'rate'=>'integer',
    ];
    public function school(){
    return $this->belongsTo(School::class);
    }
}
