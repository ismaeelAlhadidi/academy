<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Blob;
use Auth;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(! Auth::guard('admin')->check()) {
            if(! Auth::guard('web')->check()) return abort('404');
            if(! $blob = $this->getBlob($request->path())) return abort('404');
            if(! $id = Auth::guard('web')->user()->id) return abort('404');
            if($this->notValid($blob, $id)) return abort('404');
        }
        return $next($request);
    }

    private function getBlob($path) {
        return Blob::where('public_route' ,$path)->first();
    }
    
    private function notValid($blob, $id) {
        if(! $blob->avalibile) return true;
        if($blob->availability_time != null) if(time() < $blob->availability_time) return true;
        $playlists = $blob->playlists;
        if(! $playlists || $playlists->count() == 0) return false;
        foreach ($playlists as $playlist) {
            $subscriptions = $playlist->subscriptions;
            foreach ($subscriptions as $subscription) {
                if($subscription->user_id == $id) return false;
            }
        }
        return true;
    }
}
