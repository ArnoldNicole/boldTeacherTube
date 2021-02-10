<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewQuestion extends Model
{
	use SoftDeletes;
    protected $guarded = [];
    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function reviews(){
    	return $this->hasMany(Review::class);
    }
}
