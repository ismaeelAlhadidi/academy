<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Audio extends Model
{
    use Notifiable;
    protected $table = 'audio';
    protected $fillable = [
        'pre_title', 'title',  'availability_time', 'src', 'driver', 'mimi_type', 'poster_src',
        'available', 'description', 'hls_src','created_at','updated_at'
    ];
    protected $hidden = ['driver', 'src'];
    public $timestamps = true;

    public function blob(){
        return $this->morphOne('App\Models\Blob', 'blobable');
    }
}
