<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserNotifaction extends Model
{
    use Notifiable;
    protected $table = 'user_notifactions';
    protected $fillable = [
        'type', 'n_id','readed', 'user_id','created_at','updated_at'
    ];
    protected $hidden = [];
    public $timestamps = true;
}
