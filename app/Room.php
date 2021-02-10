<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
	use SoftDeletes;
	
    protected $guarded = [];
    public function teacher(){
    	return $this->belongsTo(User::class);
    }
    public function students(){
    	return $this->belongsToMany(User::class);
    }
    public function notes(){
    	return $this->belongsToMany(Note::class);
    }
}
