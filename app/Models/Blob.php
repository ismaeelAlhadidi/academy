<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Blob extends Model
{
    use Notifiable;
    protected $table = 'objects';
    protected $fillable = [
        'blobable_id', 'blobable_type', 'public_route','type_id'
    ];
    protected $hidden = ['blobable_id'];
    public $timestamps = false;

    public function type() {
        return $this->belongsTo('App\Models\Type', 'type_id', 'id');
    }
    public function blobable() {
        return $this->morphTo();
    }
    public function views() {
        return $this->hasMany('App\Models\View','user_id','id');
    }
    public function playlists() {
        return $this->belongsToMany('App\Models\Playlist','playlist_object','object_id','playlist_id','id','id');
    }
}
