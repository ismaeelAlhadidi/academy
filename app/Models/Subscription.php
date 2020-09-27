<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscription extends Model
{
    use Notifiable;
    protected $table = 'subscriptions';
    protected $fillable = [
        'playlist_id', 'user_id', 'payment_id','access', 'created_at', 'updated_at'
    ];
    protected $hidden = ['payment_id'];
    public $timestamps = true;

    public function playlist() {
        return $this->belongsTo('App\Models\Playlist', 'playlist_id', 'id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function payment() {
        return $this->belongsTo('App\Models\Payment', 'payment_id', 'id');
    }
    public function blobs() {
        return $this->hasManyThrough('App\Models\Blob', 'App\Models\Playlist');
    }
}
