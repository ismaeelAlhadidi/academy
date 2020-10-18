<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'second_name', 'last_name', 'email', 'image','password',
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


    public function playlistOpinions() {
        return $this->hasMany('App\Models\PlaylistOpinion','user_id','id');
    }
    public function comments() {
        return $this->hasMany('App\Models\Comment','user_id','id');
    }
    public function replays() {
        return $this->hasMany('App\Models\Replay','user_id','id');
    }
    public function coachOpinions() {
        return $this->hasMany('App\Models\CoachOpinion','user_id','id');
    }
    public function subscriptions() {
        return $this->hasMany('App\Models\Subscription','user_id','id');
    }
    public function sessionsOnlines() {
        return $this->hasMany('App\Models\SessionsOnline','user_id','id');
    }
    public function usersIps() {
        return $this->hasMany('App\Models\UsersIp','user_id','id');
    }
    public function views() {
        return $this->hasMany('App\Models\View','user_id','id');
    }
    public function singleVideoForms() { 
        return $this->hasMany('App\Models\SingleVideoForm','single_video_form_id','id');
    }
}
