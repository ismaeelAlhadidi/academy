<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\VisiteMyApp;
use App\Models\Visiter;
use App\Models\UsersIp;
use App\Models\VisiterRoute;
class InsertVisite
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(VisiteMyApp $eventOpject) {
        if(! $eventOpject->visiter) {
            $visiter = $this->addNewVisiter($eventOpject->ip,$eventOpject->mac,$eventOpject->device_data,$eventOpject->ref,$eventOpject->route);
        } else {
            $this->visitesIncrement($eventOpject->ref,$eventOpject->route,$eventOpject->visiter->id);
            $visiter = $eventOpject->visiter;
        }
        $key = 'visited' . $eventOpject->route;
        if(! $visiter ) return;
        session([$key => true]);
        session(['visiter' => $visiter->id]);
        if($eventOpject->user_id == null) return;
        $this->saveUserIp($eventOpject->user_id, $visiter->id);
    }

    public function addNewVisiter($ip,$mac,$device_data,$ref,$route) {
        $visiter = Visiter::create([
            'ip_address' => $ip,
            'device_data' => $device_data,
            'mac_address' => $mac,
        ]);
        if(! $visiter) return $visiter;
        VisiterRoute::create([
            'visiter_id' => $visiter->id,
            'reference' => $ref,
            'count' => 1,
            'route' => $route
        ]);
    }

    public function visitesIncrement($ref,$route,$visiter_id) {
        $visiterRoute = VisiterRoute::where('reference' ,$ref) -> where('route' ,$route)->first();
        if(! $visiterRoute) {
            VisiterRoute::create([
                'visiter_id' => $visiter_id,
                'reference' => $ref,
                'count' => 1,
                'route' => $route
            ]);
        }
        else {
            $count = $visiterRoute->count + 1;
            $visiterRoute->update(['count' => $count]);
        }
    }

    public function saveUserIp($user_id, $visiter_id) {
        $user_ip = UsersIp::where('user_id',$user_id)
            ->where('visiter_id',$visiter_id)->first();

        if(! $user_ip) {
            UsersIp::create([
                'user_id' => $user_id,
                'visiter_id' => $visiter_id
            ]);
        }
    }
}
