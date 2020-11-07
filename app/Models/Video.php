<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Video extends Model
{
    use Notifiable;
    protected $table = 'videos';
    protected $fillable = [
        'pre_title', 'title', 'availability_time', 'src', 'driver', 'mimi_type',
        'available', 'poster_src', 'form_key', 'hls_src', 'created_at','updated_at'
    ];
    protected $hidden = ['driver', 'src'];
    public $timestamps = true;

    /* make auto delete */

    public function blob() {
        return $this->morphOne('App\Models\Blob', 'blobable');
    }
    public function singleVideoForms() {
        return $this->hasMany('App\Models\SingleVideoForm','single_video_form_id','id');
    }
}
