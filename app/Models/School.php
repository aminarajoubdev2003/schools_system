<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'title',
        'address',
        'boss_id',
        'logo',
        'phone',
        'mobile',
        'installment',
        'transportation_cost',
    ];
    protected $casts=[
        'uuid'=>'string',
        'title'=>'string',
        'address'=>'string',
        'boss_id' => 'integer',
        'logo'=>'string',
        'phone'=>'string',
        'mobile'=>'string',
        'installment'=>'integer',
        'transportation_cost'=>'integer',
    ];
    
    public function boss(){
    return $this->belongsTo(Boss::class);
    }
    public function classroms(){
        return $this->hasMany(Classrom::class);
    }
    public function teachers(){
        return $this->hasMany(Teacher::class);
    }
    public function transportations(){
        return $this->hasMany(Transportation::class);
    }
    public function videos(){
        return $this->hasMany(Video::class);
    }
    public function informations(){
        return $this->hasMany(Information::class);
    }
    public function payments(){
        return $this->hasMany(Payment::class);
    }
    public function opinions(){
        return $this->hasMany(Opinion::class);
    }
    
                                  
        
}
