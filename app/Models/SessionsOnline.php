<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\AdminNotifaction; 
use App\Models\UserNotifaction;

class SessionsOnline extends Model
{
    use Notifiable;
    protected $table = 'sessions_onlines';
    protected $fillable = [
        'sessions_offer_id', 'user_id', 'payment_id','time', 'admission', 
        'taken', 'request_time'
    ];
    protected $hidden = ['payment_id'];
    public $timestamps = false;
    public static function boot()
	{
        parent::boot();
		static::created(function ($model) {
            $data = [
                'type' => 'SessionsOnline',
                'n_id' => $model->id,
            ];
	        AdminNotifaction::create($data);
        });
    }
    public function sessionOffer() {
        return $this->belongsTo('App\Models\SessionsOffer', 'sessions_offer_id', 'id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function payment() {
        return $this->belongsTo('App\Models\Payment', 'payment_id', 'id');
    }

    public function setAdmissionAttribute($value) {
        $oldValue = $this->attributes['admission'];
        $this->attributes['admission'] = $value;
        try {
            if($oldValue != $value) {
                $data = [
                    'type' => 'SessionsOnline',
                    'n_id' => $this->attributes['id'],
                    'user_id' => $this->attributes['user_id']
                ];
                UserNotifaction::create($data);
            }
        } catch(Exception $ex) { }
    }
}
