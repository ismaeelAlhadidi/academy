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
use FFMpeg;

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
        $this->saveView($blob->id);
        //return response()->file(storage_path('app' . $path));
        $url = storage_path('app' . $path);
        $headers = [
            'Content-Type'        => $blob->blobable->mimi_type,
            'Content-Length'      => Storage::disk($driver)->size($path),
            'Content-Disposition' => 'attachment; filename="' . $url . '.ts"'
        ];
        return response()->stream(function() use ($url) {
            try {
                $stream = fopen($url, 'r');
                fpassthru($stream);
            } catch(Exception $e) {
                Log::error($e);
            }
        }, 200, $headers);
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
        $this->saveView($blob->id);
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
        $this->saveView($blob->id);
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

        if($this->blobIsAvailable($availabilityTime, $subscriptionTime, $playlistTime)) {
            $video = $blob->blobable->src;
            $driver = $blob->blobable->driver;
            $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'. DIRECTORY_SEPARATOR . $video;
            $media = FFMpeg::fromDisk($driver)->open($path);
            //return dd($media()->getStreams());
            $codec = $media()->getStreams()->first()->get('codec_name');
            return $this->getResponse(true, '', [
                'mimi' => $blob->blobable->mimi_type .'; codecs="' . $codec . '"',
                'title' => $blob->blobable->title, 
                'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')
            ]);
        }
        return $this->getResponse(false, 'videoTime', ['title' => $blob->blobable->title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);
    }

    private function saveView($blob_id) {
        if(Auth::guard('web')->check()){
            $key = 'view' . $blob_id;
            if(session()->has('visiter') && ! session()->has($key)) {
                $user_id = Auth::guard('web')->user()->id;
                $visiter_id = session()->get('visiter');
                event(new ShowBlob($visiter_id,$user_id,$blob_id));
            }
        }
    }
}
