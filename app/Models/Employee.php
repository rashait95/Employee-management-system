<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Employee extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'position',
        'department_id',
    ];

        public function department(){

            return $this->belongsTo(Department::class);
        }


        
    public function projects(){
        return $this->belongsToMany(Project::class);
    }
    

    public function notes() : MorphMany {

        return $this->morphMany(Note::class,'notable');
    


        
    }


    public function setFirstNameAttribute($value)
    {
       $this->attributes['first_name'] = ucfirst(strtolower($value));
    }


    public function setLastNameAttribute($value){
        $this->attributes['last_name'] = ucfirst(strtolower($value));
    }

    public function getFirstNameAttribute($value)
    {
        return ucfirst(strtolower($value));
    }

    public function getLastNameAttribute($value) {
        return ucfirst(strtolower($value));
    }


}
