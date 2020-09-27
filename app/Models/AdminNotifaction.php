<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AdminNotifaction extends Model
{
    use Notifiable;
    protected $table = 'admin_notifactions';
    protected $fillable = [
        'type', 'n_id','readed', 'created_at','updated_at'
    ];
    protected $hidden = ['driver', 'src'];
    public $timestamps = true;
}
