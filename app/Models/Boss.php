<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boss extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'start_year',
        'image',
    ];
    protected $casts=[
        'uuid'=>'string',
        'name'=>'string',
        'start_year'=>'integer',
        'image'=>'string',
    ];

    public function school(){
    return $this->hasOne(School::class);
    }
    
}
