<?php

namespace App\Http\Controllers\Authenticated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserNotifaction;
use App\Models\Replay;
use App\Models\SessionsOnline;
use App\Traits\AjaxResponse;
use App\Traits\FormatTime;

class NotificationController extends Controller
{
    use AjaxResponse, FormatTime;

    public function setReaded($type, $id) {
        $notification = UserNotifaction::where('type', $type)->where('n_id', $id)->first();
        if($notification) $notification->update(['readed' => 1]);
    }
    public function getReplay($id) {
        $replay = Replay::find($id);
        if(! $replay) return $this->getResponse(false, __('masseges.general-error'), null);
        $data = array (
            'image' => $replay->user->image,
            'name' => $replay->user->first_name . ' ' . $replay->user->last_name,
            'time' => $this->convertToBeforeFormat($replay->created_at),
            'content' => $replay->content,
            'playlist_title' => $replay->comment->playlist->title,
            'replay_url' => route('playlist.show', $replay->comment->playlist->id) . '?replay=' . $replay->id,
        );
        return  $this->getResponse(true, '', $data);
    }
    public function getOffer($id) {
        $session = SessionsOnline::find($id);
        if(! $session) return $this->getResponse(false, '', []);
        //('admission') && jsonResponse.data.hasOwnProperty('sessionsUrl')
        return $this->getResponse(true, '', [
            'offerId' => $session->sessionOffer->id,
            'offerName' => $session->sessionOffer->name,
            'admission' => $session->admission,
            'sessionsUrl' => route('my.sessions') . '?session=' . $session->id,
        ]);
    }
}
