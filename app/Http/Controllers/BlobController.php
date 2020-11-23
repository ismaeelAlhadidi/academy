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
use FFMpeg\Format\Video\X264;
use App\jobs\convertMediaToHls;
use Str;
use Cookie;

class BlobController extends Controller
{
    use AjaxResponse,FormatTime;
    public function getVideo($video) {
        $blob = Blob::where('public_route', $video)->first();
        if(! $blob) return abort('404');
        $video = $blob->blobable->src;
        $driver = $blob->blobable->driver;
        $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'. DIRECTORY_SEPARATOR . $video;
        if (! Storage::disk($driver)->exists($path)) {
            return abort('404');
        }
        return response()->file(storage_path('app' . $path));
    }
    public function getWatch($video) {
        $blob = Blob::where('public_route', $video)->first();
        if(! $blob) {
            $tempArray = explode('_', $video);
            if(! session()->has('opendPlaylist')) abort('404');
            $playlistId = session()->get('opendPlaylist');
            $directory = 'playlist' . $playlistId;   
            /*
            $hls_src = $directory . '\\' . $tempArray[0] . '.m3u8';
            $file = Video::where('hls_src', $hls_src)->first();
            if(! $file) return abort('404');
            if(! $file->available) {
                if(! session()->has('playlistOpendToWatch' . $playlistId)) abort('404');
                return dd(request()->cookie());
                if(session()->get('playlistOpendToWatch' . $playlistId) != Cookie::get('wt')) abort('403');
            }
            */
            return $this->getStream($directory . DIRECTORY_SEPARATOR . $video);
        }
        $video = $blob->blobable->hls_src;
        $driver = $blob->blobable->driver;
        if(! $video) return abort('404');
        $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'hls'. DIRECTORY_SEPARATOR . $video;
        if (! Storage::disk($driver)->exists($path)) {
            return abort('404');
        }
        if(! session()->has('opendPlaylist')) {
            $playlistId = $blob->playlists->first()->id;
            session(['opendPlaylist' => $playlistId]);
        }
        $this->saveView($blob->id);
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

        $subscription = Subscription::where('playlist_id', $playlistId)->where('user_id', auth()->user()->id)->where('access', 1)->first();

        if(! $blob->blobable->available && ! $subscription) return $this->getResponse(false, 'needSub', ['title' => $blob->blobable->pre_title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);

        if($subscription) $subscriptionTime = $subscription->created_at;
        else $subscriptionTime = null;
        
        if($playlist->availability_time != null) $playlistTime = $playlist->availability_time;
        else $playlistTime = $playlist->created_at;

        $availabilityTime = $blob->blobable->availability_time;
        if(! $blob->blobable->available) {
            if(! $playlist->available) {
                if($playlist->availability_time == null) {
                    return $this->getResponse(false, 'playlistNotAvailable', ['title' => $blob->blobable->title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);
                } else if(strtotime($playlistTime) > time()) {
                    return $this->getResponse(false, 'playlistNotAvailable', ['title' => $blob->blobable->title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);
                }
            }
        }
        if($blob->blobable->available || $this->blobIsAvailable($availabilityTime, $subscriptionTime, $playlistTime)) {
            session(['opendPlaylist' => $playlistId]);
            return $this->getResponse(true, '', [
                'title' => $blob->blobable->title, 
                'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : ''),
            ]);
        }
        return $this->getResponse(false, 'videoTime', ['title' => $blob->blobable->title, 'desc' => ( ($blob->blobable_type != 'App\Models\Video') ? $blob->blobable->description : '')]);
    }
    private function getStream($video) {
        $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'hls'. DIRECTORY_SEPARATOR . $video;
        return response()->file(storage_path('app' . $path));
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
    
    public function testHls() {
        /*
        $blob = Blob::find(2);
        $video = $blob->blobable->src;
        $driver = $blob->blobable->driver;
        $path = 'private' . DIRECTORY_SEPARATOR . 'video'. DIRECTORY_SEPARATOR . $video;
        */
        // making hls 
        /*
        $lowBitrate = (new X264)->setKiloBitrate(250)->setAudioCodec("libmp3lame");
        $midBitrate = (new X264)->setKiloBitrate(500)->setAudioCodec("libmp3lame");
        $highBitrate = (new X264)->setKiloBitrate(1000)->setAudioCodec("libmp3lame");
        $pathAsArray = explode('.', $video);
        array_pop($pathAsArray);
        $newPath = 'private' . DIRECTORY_SEPARATOR . 'hls' . DIRECTORY_SEPARATOR . implode($pathAsArray) . '.m3u8';
        FFMpeg::fromDisk($driver)
            ->open($path)
            ->exportForHLS()
            ->toDisk($driver)
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save($newPath);
        FFMpeg::cleanupTemporaryFiles();
        */
        
        /*$driver = 'local';
        $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'hls'. DIRECTORY_SEPARATOR . 'playlist1\T0ut5i5f5068b8287d75-19494637-1599105208_2_1000_00000.ts';
        $media = FFMpeg::fromDisk($driver)->open($path);
        $codec = $media()->getStreams()->first()->get('codec_name');
        FFMpeg::cleanupTemporaryFiles();
        return $codec;*/

        /* if(access is ok) */
        /* 1 => session */ 
        /* 2 => cokies */
        /*
        $video = Video::find(44);
        $path = 'private' . DIRECTORY_SEPARATOR . 'video'. DIRECTORY_SEPARATOR . $video->src;
        $pathAsArray = explode('.', $video->src);
        array_pop($pathAsArray);
        $newPath = 'private' . DIRECTORY_SEPARATOR . 'hls' . DIRECTORY_SEPARATOR . implode($pathAsArray) . '.m3u8';
        */
        /*dispatch(new convertMediaToHls($video, $path, $video->driver, $newPath));*/
        /*
        $lowBitrate = (new X264)->setKiloBitrate(250)->setAudioCodec("libmp3lame");
        $midBitrate = (new X264)->setKiloBitrate(500)->setAudioCodec("libmp3lame");
        $highBitrate = (new X264)->setKiloBitrate(1000)->setAudioCodec("libmp3lame");
        FFMpeg::fromDisk($video->driver)
            ->open($path)
            ->exportForHLS()
            ->toDisk($video->driver)
            ->addFormat($lowBitrate)
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save($newPath);
        FFMpeg::cleanupTemporaryFiles();
        $video->update(['hls_src' => $newPath]);
        return 'start job';*/
    }
}
