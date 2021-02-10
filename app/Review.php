<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
   use SoftDeletes;

   protected $guarded = [];

   public function review_question(){
   	return $this->belongsTo(ReviewQuestion::class);
   }
   public function user()
   {
   	return $this->belongsTo(User::class);
   }
}
