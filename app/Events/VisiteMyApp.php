<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VisiteMyApp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $visiter,$ip,$mac,$device_data,$route,$ref,$user_id;


    public function __construct($visiter,$ip,$mac,$device_data,$route,$ref,$user_id = null)
    {
        $this->visiter = $visiter;
        $this->ip = $ip;
        $this->mac = $mac;
        $this->device_data = $device_data;
        $this->route = $route;
        $this->ref = $ref;
        $this->user_id = $user_id;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
