<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class VisiterRoute extends Model
{
    use Notifiable;
    protected $table = 'visiter_routes';
    protected $fillable = [
        'visiter_id', 'reference', 'count', 'route', 'created_at', 'updated_at'
    ];
    protected $hidden = [];
    public $timestamps = true;

    public function visiter() {
        return $this->belongsTo('App\Models\Visiter', 'visiter_id', 'id');
    }
}
