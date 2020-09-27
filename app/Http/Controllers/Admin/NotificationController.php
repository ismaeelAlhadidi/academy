<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoachOpinion;
use App\Models\PlaylistOpinion;
use App\Models\AdminNotifaction;
use App\Models\Comment;
use App\Models\Replay;
use App\Models\SessionsOnline;
use App\Traits\AjaxResponse;

class NotificationController extends Controller
{
    use AjaxResponse;

    public function setReaded($type, $id) {
        $notification = AdminNotifaction::where('type', $type)->where('n_id', $id)->first();
        if($notification) $notification->update(['readed' => 1]);
    }

    public function getCoachOpinion($id) {
        $opinion = CoachOpinion::find($id);
        if(! $opinion) return $this->getResponse(false, __('masseges.general-error'), null);
        $data = array (
            'image' => $opinion->user->image,
            'name' => $opinion->user->first_name . ' ' . $opinion->user->last_name,
            'time' => Date('F j, Y, g:i a',strtotime($opinion->created_at)),
            'content' => $opinion->content,
            'allow' => $opinion->allow,
        );
        return  $this->getResponse(true, '', $data);
    }

    public function getPlaylistOpinion($id) {
        $opinion = PlaylistOpinion::find($id);
        if(! $opinion) return $this->getResponse(false, __('masseges.general-error'), null);
        $data = array (
            'image' => $opinion->user->image,
            'name' => $opinion->user->first_name . ' ' . $opinion->user->last_name,
            'time' => Date('F j, Y, g:i a',strtotime($opinion->created_at)),
            'content' => $opinion->content,
            'playlist_title' => $opinion->playlist->title,
            'allow' => $opinion->allow,
        );
        return  $this->getResponse(true, '', $data);
    }

    public function getComment($id) {
        $comment = Comment::find($id);
        if(! $comment) return $this->getResponse(false, __('masseges.general-error'), null);
        $data = array (
            'image' => $comment->user->image,
            'name' => $comment->user->first_name . ' ' . $comment->user->last_name,
            'time' => Date('F j, Y, g:i a',strtotime($comment->created_at)),
            'content' => $comment->content,
            'playlist_title' => $comment->playlist->title,
            'allow' => $comment->allow,
        );
        return  $this->getResponse(true, '', $data);
    }

    public function getReplay($id) {
        $replay = Replay::find($id);
        if(! $replay) return $this->getResponse(false, __('masseges.general-error'), null);
        $data = array (
            'image' => $replay->user->image,
            'name' => $replay->user->first_name . ' ' . $replay->user->last_name,
            'time' => Date('F j, Y, g:i a',strtotime($replay->created_at)),
            'content' => $replay->content,
            'playlist_title' => $replay->comment->playlist->title,
            'allow' => $replay->allow,
        );
        return  $this->getResponse(true, '', $data);
    }

    public function getSessionsOnline($id) {
        $session = SessionsOnline::find($id);
        if(! $session) return $this->getResponse(false, __('masseges.general-error'), null);
        $data = array (
            'image' => $session->user->image,
            'name' => $session->user->first_name . ' ' . $session->user->last_name,
            'time' => Date('F j, Y, g:i a',strtotime($session->time)),
            'offer_name' => $session->sessionOffer->name,
            'admission' => $session->admission,
        );
        return  $this->getResponse(true, '', $data);
    }
}
