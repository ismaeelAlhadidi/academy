<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UsersIp extends Model
{
    use Notifiable;
    protected $table = 'users_ips';
    protected $fillable = [
        'visiter_id', 'user_id', 'created_at', 'updated_at'
    ];
    protected $hidden = [];
    public $timestamps = true;

    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function visiter() {
        return $this->belongsTo('App\Models\Visiter','visiter_id','id');
    }
}
