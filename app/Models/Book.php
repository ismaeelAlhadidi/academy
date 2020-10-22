<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Book extends Model
{
    use Notifiable;
    protected $table = 'books';
    protected $fillable = [
        'pre_title', 'title','src', 'availability_time', 'driver', 'mimi_type', 'poster_src',
        'available', 'description','created_at','updated_at'
    ];
    protected $hidden = ['driver', 'src'];
    public $timestamps = true;

    public function blob() {
        return $this->morphOne('App\Models\Blob', 'blobable');
    }
}
