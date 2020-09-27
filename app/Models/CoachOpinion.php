<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Models\AdminNotifaction; 

class CoachOpinion extends Model
{
    use Notifiable;
    protected $table = 'coach_opinions';
    protected $fillable = [
        'user_id', 'content','allow', 'created_at','updated_at'
    ];
    public $timestamps = true;

    public static function boot()
	{
        parent::boot();
		static::created(function ($model) {
            $data = [
                'type' => 'CoachOpinion',
                'n_id' => $model->id,
            ];
	        AdminNotifaction::create($data);
        });
    }
    
    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
}
