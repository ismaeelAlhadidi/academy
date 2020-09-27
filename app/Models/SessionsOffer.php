<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SessionsOffer extends Model
{
    use Notifiable;
    protected $table = 'sessions_offers';
    protected $fillable = [
        'name', 'for_who', 'for_who_not','notes', 'benefits', 
        'price', 'duration', 'poster'
    ];
    protected $hidden = [];
    public $timestamps = false;
    
    // delete poster in deleting
     
    public function sessionsOnlines() {
        return $this->hasMany('App\Models\SessionsOnline','sessions_offer_id','id');
    }
}
