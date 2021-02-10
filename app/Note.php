<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded =[];
    public function classes(){
    	return $this->belongsToMany(Room::class);
    }
}
