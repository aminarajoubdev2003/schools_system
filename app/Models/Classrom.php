<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classrom extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'stage',
        'level',
        'branch',
    ];
    protected $casts=[
        'uuid'=>'string',
        'stage'=>'string',
        'level'=>'string',
        'branch'=>'string',
    ];

    public function categories(){
    return $this->hasMany(Category::class);
    }
    public function medias(){
    return $this->hasMany(Media::class);
    }
    public function files(){
    return $this->hasMany(File::class);
    }
    public function applications(){
    return $this->hasMany(Application::class);
    }
    
}
