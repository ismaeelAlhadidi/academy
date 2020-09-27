<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class View extends Model
{
    use Notifiable;
    protected $table = 'views';
    protected $fillable = [
        'user_id', 'visiter_id', 'object_id', 'count', 'created_at', 'updated_at'
    ];
    protected $hidden = [];
    public $timestamps = true;

    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function visiter() {
        return $this->belongsTo('App\Models\Visiter','visiter_id','id');
    }
    public function blob() {
        return $this->belongsTo('App\Models\Blob','object_id','id');
    }
}
