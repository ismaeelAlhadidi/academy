<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Type extends Model
{
    use Notifiable;
    protected $table = 'types';
    protected $fillable = ['name', 'description'];
    public $timestamps = false;

    public function blobs() {
        return $this->hasMany('App\Models\Blob', 'type_id', 'id');
    }
    public function playlists() {
        return $this->belongsToMany('App\Models\Playlist','playlist_type','type_id','playlist_id','id','id');
    }
}
