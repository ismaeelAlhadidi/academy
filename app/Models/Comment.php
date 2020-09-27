<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\AdminNotifaction; 

class Comment extends Model
{
    use Notifiable;
    protected $table = 'comments';
    protected $fillable = ['playlist_id', 'user_id', 'content', 'allow', 'created_at', 'updated_at'];
    protected $hidden = [];
    public $timestamps = true;

    public static function boot()
	{
        parent::boot();
		static::created(function ($model) {
            $data = [
                'type' => 'Comment',
                'n_id' => $model->id,
            ];
	        AdminNotifaction::create($data);
        });
    }
    
    public function playlist() {
        return $this->belongsTo('App\Models\Playlist','playlist_id','id');
    }
    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function replays() {
        return $this->hasMany('App\Models\Replay','comment_id','id');
    }
}
