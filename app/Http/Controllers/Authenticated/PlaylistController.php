<?php

namespace App\Http\Controllers\Authenticated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AjaxResponse;
use App\Traits\FormatTime;
use App\Models\Playlist;
use App\Models\Comment;
use App\Models\Replay;
use App\Models\Subscription;
/*
use Cookie;
use Str;
*/
class PlaylistController extends Controller
{
    use AjaxResponse,FormatTime;
    private $commentsCountOnOneScroll = 10;
    public function index($id = null) {
        $commentsCountOnOneScroll = $this->commentsCountOnOneScroll;
        if($id == null) return abort('404');
        $playlist = Playlist::find($id);
        if(! $playlist) return abort('404');
        $firstComment = null;
        if(request()->has('replay')) {
            $replay = Replay::find(request()->get('replay'));
            if(! $replay) $firstComment = null;
            else {
                $firstComment = $replay->comment;
            }
        }
        $comments = Comment::where('allow', 1)->orWhere('user_id', auth()->user()->id)->where('playlist_id', $playlist->id)->orderBy('id', 'desc')->paginate($commentsCountOnOneScroll);
        if($firstComment != null) {
            $replays = $firstComment->replays->filter(function($value) {
                return ($value->allow);
            });
            $replays = $replays->transform(function($replay) {
                $data = array (
                    'name' => $replay->user->first_name . ' ' . $replay->user->last_name,
                    'image' => asset($replay->user->image),
                    'time' => $this->convertToBeforeFormat($replay->created_at),
                    'content' => $replay->content,
                    'userId' => $replay->user->id,
                    'id' => $replay->id,
                );
                return $data;
            });
            $firstComment = $data = array (
                'name' => $firstComment->user->first_name . ' ' . $firstComment->user->last_name,
                'image' => asset($firstComment->user->image),
                'time' => $this->convertToBeforeFormat($firstComment->created_at),
                'content' => $firstComment->content,
                'userId' => $firstComment->user->id,
                'replays' => $replays,
                'id' => $firstComment->id,
            );
        }
        $comments->transform(function ($comment) use ($firstComment) {
            if($firstComment != null) {
                if($comment->id == $firstComment['id']) return null;
            }
            $replays = $comment->replays->filter(function($value) {
                return ($value->allow);
            });
            $replays = $replays->transform(function($replay) {
                $data = array (
                    'name' => $replay->user->first_name . ' ' . $replay->user->last_name,
                    'image' => asset($replay->user->image),
                    'time' => $this->convertToBeforeFormat($replay->created_at),
                    'content' => $replay->content,
                    'userId' => $replay->user->id,
                    'id' => $replay->id,
                );
                return $data;
            });
            $data = array (
                'name' => $comment->user->first_name . ' ' . $comment->user->last_name,
                'image' => asset($comment->user->image),
                'time' => $this->convertToBeforeFormat($comment->created_at),
                'content' => $comment->content,
                'userId' => $comment->user->id,
                'replays' => $replays,
                'id' => $comment->id,
            );
            return $data;
        });

        $subscription = Subscription::where('user_id', auth()->user()->id)->where('playlist_id', $playlist->id)->first();
        if($subscription) {
            $subscriptionTime = $subscription->created_at;
            $isSubscription = true;
        }
        else {
            $subscriptionTime = null;
            $isSubscription = false;
        }
        if($playlist->availability_time != null) $playlistTime = $playlist->availability_time;
        else $playlistTime = $playlist->created_at;

        $firstBlob = $playlist->blobs->first();
        if($firstBlob) {
            $firstBlob['blobType'] = ($firstBlob->blobable_type == "App\Models\Video") 
                ? 'video' 
                : ( ($firstBlob->blobable_type == "App\Models\Audio") 
                    ? 'audio'
                    : 'book'
            );
        }

        $tempVideos = $playlist->blobs->filter(function($value) {
            return ($value->blobable_type == "App\Models\Video");
        })->all();
        $videos = array();
        if(count($tempVideos) > 0) {
            $videos['noneType'] = array();
            $videoTypes = array();
            foreach($tempVideos as $tempVideo) {

                $tempVideo->time = $this->setAvailabilityTimeOfBlob($tempVideo->blobable->availability_time, $subscriptionTime, $playlistTime);

                if($tempVideo->type_id == null) {
                    array_push($videos['noneType'], $tempVideo);
                } elseif(in_array( $tempVideo->type_id, $videoTypes)) {
                    array_push($videos[$tempVideo->type_id], $tempVideo);
                } else {
                    array_push($videoTypes, $tempVideo->type_id);
                    $videos[$tempVideo->type_id] = array();
                    array_push($videos[$tempVideo->type_id], $tempVideo);
                }
            }
            if(count($videoTypes) == 0 && count($videos['noneType']) == 0) $videos = array();
        }

        $tempBooks = $playlist->blobs->filter(function($value) {
            return ($value->blobable_type == "App\Models\Book");
        })->all();
        $books = array();
        if(count($tempBooks) > 0) {
            $books['noneType'] = array();
            $booksTypes = array();
            foreach($tempBooks as $tempBook) {

                $tempBook->time = $this->setAvailabilityTimeOfBlob($tempBook->blobable->availability_time, $subscriptionTime, $playlistTime);

                if($tempBook->type_id == null) {
                    array_push($books['noneType'], $tempBook);
                } elseif(in_array( $tempBook->type_id, $booksTypes)) {
                    array_push($books[$tempBook->type_id], $tempBook);
                } else {
                    array_push($booksTypes, $tempBook->type_id);
                    $books[$tempBook->type_id] = array();
                    array_push($books[$tempBook->type_id], $tempBook);
                }
            }
            if(count($booksTypes) == 0 && count($books['noneType']) == 0) $books = array();
        }

        $tempAudios = $playlist->blobs->filter(function($value) {
            return ($value->blobable_type == "App\Models\Audio");
        })->all();
        $audios = array();
        if(count($tempAudios) > 0) {
            $audios['noneType'] = array();
            $audiosTypes = array();
            foreach($tempAudios as $tempAudio) {

                $tempAudio->time = $this->setAvailabilityTimeOfBlob($tempAudio->blobable->availability_time, $subscriptionTime, $playlistTime);

                if($tempAudio->type_id == null) {
                    array_push($audios['noneType'], $tempAudio);
                } elseif(in_array( $tempAudio->type_id, $audiosTypes)) {
                    array_push($audios[$tempAudio->type_id], $tempAudio);
                } else {
                    array_push($audiosTypes, $tempAudio->type_id);
                    $audios[$tempAudio->type_id] = array();
                    array_push($audios[$tempAudio->type_id], $tempAudio);
                }
            }
            if(count($audiosTypes) == 0 && count($audios['noneType']) == 0) $audios = array();
        }
        /*
        if($isSubscription) {
            $wt = $this->generateWatchToken();
            session(['playlistOpendToWatch' . $id => $wt]);
            // setcookie('name', 'value', $minutes, $path, $domain, $secure, $httpOnly);
            Cookie::queue(Cookie::make('wt', $wt, time() + 3*60, "/playlist/$id", asset('/'), true, true));
        }
        */
        return view('authenticated.playlist', [
            'playlist' => $playlist,
            'comments' => $comments,
            'commentsCountOnOneScroll' => $commentsCountOnOneScroll,
            'videos' => $videos,
            'books' => $books,
            'audios' => $audios,
            'firstBlob' => $firstBlob,
            'isSubscription' => $isSubscription,
            'firstComment' => $firstComment,
            'targetReplay' => ( request()->has('replay') ? request()->get('replay') : -1),
        ]);
    }
    public function getMoreComments($id) {
        $firstComment = null;
        if(request()->has('replay')) {
            $replay = Replay::find(request()->get('replay'));
            if(! $replay) $firstComment = null;
            else {
                $firstComment = $replay->comment;
            }
        }
        $comments = Comment::where('allow', 1)->orWhere('user_id', auth()->user()->id)->where('playlist_id', $id)->orderBy('id', 'desc')->paginate($this->commentsCountOnOneScroll);
        if(! $comments) return $this->getResponse(false, '', null);
        if($firstComment != null) {
            $replays = $firstComment->replays->filter(function($value) {
                return ($value->allow);
            });
            $replays = $replays->transform(function($replay) {
                $data = array (
                    'name' => $replay->user->first_name . ' ' . $replay->user->last_name,
                    'image' => asset($replay->user->image),
                    'time' => $this->convertToBeforeFormat($replay->created_at),
                    'content' => $replay->content,
                    'userId' => $replay->user->id,
                    'id' => $replay->id,
                );
                return $data;
            });
            $firstComment = $data = array (
                'name' => $firstComment->user->first_name . ' ' . $firstComment->user->last_name,
                'image' => asset($firstComment->user->image),
                'time' => $this->convertToBeforeFormat($firstComment->created_at),
                'content' => $firstComment->content,
                'userId' => $firstComment->user->id,
                'replays' => $replays,
                'id' => $firstComment->id,
            );
        }
        $comments->transform(function ($comment) use ($firstComment) {
            if($firstComment != null) {
                if($comment->id == $firstComment['id']) return null;
            }
            $replays = $comment->replays->filter(function($value) {
                return ($value->allow);
            });
            $replays = $replays->transform(function($replay) {
                $data = array (
                    'name' => $replay->user->first_name . ' ' . $replay->user->last_name,
                    'image' => asset($replay->user->image),
                    'time' => $this->convertToBeforeFormat($replay->created_at),
                    'content' => $replay->content,
                    'userId' => $replay->user->id,
                    'id' => $replay->id,
                );
                return $data;
            });
            $data = array(
                'name' => $comment->user->first_name . ' ' . $comment->user->last_name,
                'image' => asset($comment->user->image),
                'time' => $this->convertToBeforeFormat($comment->created_at),
                'content' => $comment->content,
                'userId' => $comment->user->id,
                'replays' => $replays,
                'id' => $comment->id,
            );
            return $data;
        });
        return $this->getResponse(true, '', $comments);
    }
    /*
    private function generateWatchToken() {
        return Str::random(6) . '-' . str_replace('.', '-', time() . '') . '-' . Str::random(4) . str_replace('.', '-',  uniqid(Str::random(6) . '-',true) . '');
    }
    */
}
