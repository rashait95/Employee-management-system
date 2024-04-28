<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];


    public function employees(){

        return $this->hasMany(Employee::class);
    }
    public function notes() : MorphMany {

        return $this->morphMany(Note::class,'notable');
    
    }
}
  

