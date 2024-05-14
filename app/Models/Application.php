<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'classrom_id',
        'url',
        'desc',
    ];
    protected $casts=[
        'uuid'=>'string',
        'title'=>'string',
        'classrom_id'=>'integer',
        'url'=>'string',
        'desc'=>'string',
    ];

    public function classrom(){
    return $this->belongsTo(Classrom::class);
    }
}
