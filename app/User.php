<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','account_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected static function boot(){
        parent::boot();

        static::created(function ($user){
            $user->profile()->create([
                'gender'=>'unknown',
                'date_of_birth'=>'unknown',
            ]);

        });

    }

    public function notes(){
        return $this->hasMany(Note::class);
    }

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function plans(){
        return $this->hasmany(Plan::class);
    }
    public function rooms(){
        return $this->hasMany(Room::class);
    }
    public function attending(){
        return $this->belongsToMany(Room::class);
    }
    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function review_questions(){
        return $this->hasMany(ReviewQuestion::class);
    }
    public function reviews(){
        return $this->hasmany(Review::class);
    }


}
