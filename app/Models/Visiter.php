<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Visiter extends Model
{
    use Notifiable;
    protected $table = 'visiters';
    protected $fillable = [
        'ip_address', 'device_data', 'mac_address'
    ];
    protected $hidden = [];
    public $timestamps = false;

    public function usersIps() {
        return $this->hasMany('App\Models\UsersIp','visiter_id','id');
    }
    public function views() {
        return $this->hasMany('App\Models\View','visiter_id','id');
    }

    public function visiterRoutes() {
        return $this->hasMany('App\Models\VisiterRoute','visiter_id','id');
    }

    public function singleVideoForms() { 
        return $this->hasMany('App\Models\SingleVideoForm','single_video_form_id','id');
    }
}
