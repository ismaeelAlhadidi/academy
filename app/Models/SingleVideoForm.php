<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SingleVideoForm extends Model
{
    use Notifiable;
    protected $table = 'single_video_forms';
    protected $fillable = [
        'video_id', 'user_id', 'visiter_id', 'first_name', 'last_name',
        'email', 'send_mail', 'created_at', 'updated_at'
    ];
    public $timestamps = true;
    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function visiter() {
        return $this->belongsTo('App\Models\Visiter','visiter_id','id');
    }
    public function video() {
        return $this->belongsTo('App\Models\Video','video_id','id');
    }
}
