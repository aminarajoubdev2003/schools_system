<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'subject',
        'classrom_id',
        'type',
        'semester',
        'path'
    ];
    protected $casts=[
        'uuid'=>'string',
        'subject'=>'string',
        'classrom_id'=>'integer',
        'type'=>'string',
        'semester'=>'string',
        'path'=>'string'
    ];

    public function classrom(){
    return $this->belongsTo(Classrom::class);
    }
}
