<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class AppInfo extends Model
{
    use Notifiable;
    protected $table = 'app_infos';
    protected $fillable = [
        'key', 'value', 'created_at','updated_at'
    ];
    protected $hidden = ['key'];
    public $timestamps = true;
}
