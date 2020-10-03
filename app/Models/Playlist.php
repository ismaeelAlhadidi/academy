<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Playlist extends Model
{
    use Notifiable;
    protected $table = 'playlists';
    protected $fillable = [ 'title', 'price', 'description', 'poster','availability_time', 'available','created_at','updated_at'];
    public $timestamps = true;

    public function playlistOpinions() {
        return $this->hasMany('App\Models\PlaylistOpinion','playlist_id','id');
    }
    public function comments() {
        return $this->hasMany('App\Models\Comment','playlist_id','id');
    }
    public function subscriptions() {
        return $this->hasMany('App\Models\Subscription','playlist_id','id');
    }
    public function blobs() {
        return $this->belongsToMany('App\Models\Blob','playlist_object','playlist_id','object_id','id','id');
    }
    public function types() {
        return $this->belongsToMany('App\Models\Type','playlist_type','playlist_id','type_id','id','id');
    }
    public function specialPlaylist() {
        return $this->belongsTo('App\Models\SpecialPlaylist', 'special_playlist_id', 'id');
    }
}
