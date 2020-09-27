<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ShowBlob;
class InsertView
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
    public function handle(ShowBlob $eventOpject)
    {
        $view = View::where('visiter_id',$eventOpject->visiter_id)
            ->where('user_id',$eventOpject->user_id)
            ->where('blob_id',$eventOpject->blob_id)->first();
        if(! $view) {
            View::create([
                'visiter_id' => $eventOpject->visiter_id,
                'user_id' => $eventOpject->user_id,
                'object_id' => $eventOpject->blob_id,
                'count' => 1
            ]);
        } else {
            $count = $view->count + 1;
            $view->update(['count' => $count]);
        }
        $key = 'view' . $eventOpject->blob_id;
        session([$key => true]);
    }
}
