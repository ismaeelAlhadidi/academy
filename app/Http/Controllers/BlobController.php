<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use App\Models\Blob;
use App\Models\Video;
use App\Models\Subscription;
use App\Models\Playlist;
use App\Events\ShowBlob;
use App\Traits\AjaxResponse;
use App\Traits\FormatTime;
use Auth;

class BlobController extends Controller
{
    use AjaxResponse,FormatTime;
    public function getVideo($video) {
        $blob = Blob::where('public_route',$video)->first();
        if(! $blob) return abort('404');
        $video = $blob->blobable->src;
        $driver = $blob->blobable->driver;
        $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'. DIRECTORY_SEPARATOR . $video;
        if (!Storage::disk($driver)->exists($path)) {
            return abort('404');
        }
        //$this->saveView($blob->id);
        return response()->file(storage_path('app' . $path));
    }

    public function getAudio($audio) {
        $blob = Blob::where('public_route',$audio)->first();
        if(! $blob) return abort('404'); 
        $audio = $blob->blobable->src;
        $driver = $blob->blobable->driver;
        $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'audio'. DIRECTORY_SEPARATOR . $audio;
        if (!Storage::disk($driver)->exists($path)) {
            return abort('404');
        }
        //$this->saveView($blob->id);
        return response()->file(storage_path('app' . $path));
    }

    public function getBook($book) {
        $blob = Blob::where('public_route',$book)->first();
        if(! $blob) return abort('404'); 
        $book = $blob->blobable->src;
        $driver = $blob->blobable->driver;
        $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'book'. DIRECTORY_SEPARATOR . $book;
        if (!Storage::disk($driver)->exists($path)) {
            return abort('404');
        }
        //$this->saveView($blob->id);
        return response()->file(storage_path('app' . $path));
    }

    public function checkBlobPermision($playlistId, $id) {
        $blob = Blob::find($id);
        if(! $blob) return abort('404');
        $playlist = Playlist::find($playlistId);
        if(! $playlist) return abort('404');

        $subscription = Subscription::where('playlist_id', $playlistId)->where('user_id', auth()->user()->id)->first();

        if(! $subscription) return $this->getResponse(false, 'needSub', ['title' => $blob->blobable->pre_title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);

        $subscriptionTime = $subscription->created_at;

        if($playlist->availability_time != null) $playlistTime = $playlist->availability_time;
        else $playlistTime = $playlist->created_at;

        $availabilityTime = $blob->blobable->availability_time;

        if($this->blobIsAvailable($availabilityTime, $subscriptionTime, $playlistTime)) return $this->getResponse(true, '', ['title' => $blob->blobable->title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);
        return $this->getResponse(false, 'videoTime', ['title' => $blob->blobable->title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);
    }

    private function saveView($blob_id) {
        if(! Auth::gaurd('web')->check()){
            $key = 'view' . $blob_id;
            if(session()->has('visiter') && ! session()->has($key)) {
                $user_id = Auth::gaurd('web')->user()->id;
                $visiter_id = session()->get('visiter');
                event(new ShowBlob($visiter_id,$user_id,$blob_id));
            }
        }
    }
}
