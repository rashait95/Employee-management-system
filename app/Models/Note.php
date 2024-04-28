<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable =[
        'note',
        'notable_id',
        'notable_type',
        
    ];

    public function notable(){
        return $this->morphTo();
    }
}
