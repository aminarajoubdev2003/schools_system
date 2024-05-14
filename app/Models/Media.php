<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'type',
        'title',
        'subject',
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
