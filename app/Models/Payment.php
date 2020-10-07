<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Payment extends Model
{
    use Notifiable;
    protected $table = 'payments';
    protected $fillable = [ 'pay_id', 'created_at','updated_at'];
    public $timestamps = true;
}
