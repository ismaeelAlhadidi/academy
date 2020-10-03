<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SpecialPlaylist extends Model
{
    use Notifiable;
    protected $table = 'special_playlists';
    protected $fillable = [
        'playlist_id'
    ];
    protected $hidden = [];
    public $timestamps = false;
    public function playlist() {
        return $this->belongsTo('App\Models\Playlist', 'playlist_id', 'id');
    }
}
