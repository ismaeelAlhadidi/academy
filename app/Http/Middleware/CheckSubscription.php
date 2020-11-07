<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Blob;
use App\Models\Subscription;
use App\Models\Video;
use Auth;
use App\Traits\FormatTime;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    use FormatTime;
    public function handle($request, Closure $next)
    {
        if(! Auth::guard('admin')->check()) {
            if(! Auth::guard('web')->check()) return abort('404');
            if(! $blob = $this->getBlob($request->url())) return abort('404');
            if($blob->blobable->avalibile) return $next($request);
            if(! $id = Auth::guard('web')->user()->id) return abort('404');
            if($this->notValid($blob, $id)) return abort('404');
        }
        return $next($request);
    }

    private function getBlob($url) {
        $temp = explode('/', $url);
        if(count($temp) <= 0) return false;
        $public_route = $temp[count($temp)-1];
        $blob = Blob::where('public_route' ,$public_route)->first();
        if(! $blob) {
            if(! session()->has('opendPlaylist')) return $blob;
            $tempArray = explode('_', $public_route);
            $playlistId = session()->get('opendPlaylist');
            $directory = 'playlist' . $playlistId;
            $hls_src = $directory . '\\' . $tempArray[0] . '.m3u8';
            $file = Video::where('hls_src', $hls_src)->first();
            if(! $file) return $blob;
            return $file->blob;
        }
        return $blob;
    }
    
    private function notValid($blob, $id) {
        $playlists = $blob->playlists;
        if(! $playlists || $playlists->count() == 0) return false;
        foreach ($playlists as $playlist) {
            $subscription = Subscription::where('playlist_id', $playlist->id)->where('user_id', $id)->first();
            if($subscription) {
                $subscriptionTime = $subscription->created_at;
                if($playlist->availability_time != null) $playlistTime = $playlist->availability_time;
                else $playlistTime = $playlist->created_at;
                $availabilityTime = $blob->blobable->availability_time;
                if($this->blobIsAvailable($availabilityTime, $subscriptionTime, $playlistTime)) return false;
            }
        }
        return true;
    }
}
