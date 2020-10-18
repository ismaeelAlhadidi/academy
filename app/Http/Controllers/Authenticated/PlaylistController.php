<?php

namespace App\Http\Controllers\Authenticated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AjaxResponse;
use App\Traits\FormatTime;
use App\Models\Playlist;
use App\Models\Comment;

class PlaylistController extends Controller
{
    use AjaxResponse,FormatTime;
    private $commentsCountOnOneScroll = 10;
    public function index($id = null) {
        $commentsCountOnOneScroll = $this->commentsCountOnOneScroll;
        if($id == null) return abort('404');
        $playlist = Playlist::find($id);
        if(! $playlist) return abort('404');
        $comments = Comment::where('allow', 1)->orWhere('user_id', auth()->user()->id)->where('playlist_id', $playlist->id)->orderBy('id', 'desc')->paginate($commentsCountOnOneScroll);
        $comments->transform(function ($comment) {
            $replays = $comment->replays->transform(function($replay) {
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
        return view('authenticated.playlist', ['playlist' => $playlist, 'comments' => $comments, 'commentsCountOnOneScroll' => $commentsCountOnOneScroll]);
    }
    public function subscription($id = null) {
        if($id == null) return abort('404');
        return $id;
    }
    public function getMoreComments($id) {
        $comments = Comment::where('allow', 1)->orWhere('user_id', auth()->user()->id)->where('playlist_id', $id)->orderBy('id', 'desc')->paginate($this->commentsCountOnOneScroll);
        if(! $comments) return $this->getResponse(false, '', null);
        $comments->transform(function ($comment) {
            $replays = $comment->replays->transform(function($replay) {
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
}
