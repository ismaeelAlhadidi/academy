<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\AdminNotifaction;
use App\Models\UserNotifaction;

class Replay extends Model
{
    use Notifiable;
    protected $table = 'replays';
    protected $fillable = ['comment_id', 'user_id', 'content', 'allow', 'created_at', 'updated_at'];
    protected $hidden = [];
    public $timestamps = true;
    public static function boot()
	{
        parent::boot();
		static::created(function ($model) {
            $data = [
                'type' => 'Replay',
                'n_id' => $model->id,
            ];
            AdminNotifaction::create($data);
            if($model->comment->user->id != $model->user_id) {
                $data = [
                    'type' => 'Replay',
                    'n_id' => $model->id,
                    'user_id' => $model->comment->user->id
                ];
                UserNotifaction::create($data);
            }
        });
    }
    public function comment() {
        return $this->belongsTo('App\Models\Comment','comment_id','id');
    }
    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
}
