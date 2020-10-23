<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SessionsOnline;
use App\Models\Replay;
use App\Models\UserNotifaction as Notifcation;

class UserNotifcation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $notifcations;
    private $new;

    public function handle($request, Closure $next)
    {
        $this->notifcations = array();
        $this->new = 0;
        $notifcations = Notifcation::where('user_id', auth()->user()->id)->orderBy('id','desc')->limit(30)->get();
        if(! $notifcations) return $next($request);
        $this->saveNotifcations($notifcations);
        session(['notifcations' => $this->notifcations]);
        session(['newNotifcations' => $this->new]);
        return $next($request);
    }

    private function addToNotifaction($id,$image,$time,$content,$readed,$type) {
        if(! $readed) $this->new++;
        $notifcation = [
            'id' => $id,
            'image' => $image,
            'time' => $time,
            'content' => $content,
            'type' => $type,
            'readed' => $readed
        ];
        array_push($this->notifcations, $notifcation);
    }

    private function getData($id, $type) {
        switch($type) {
            case 'Replay':
                $temp = Replay::find($id);
                if(! $temp) return null;
                if($temp->allow == false) return null;
                $content = $temp->user->first_name . ' ' . __('notifcations.replay') . __('notifcations.you');
                $poster = $temp->user->image;
            break;
            case 'SessionsOnline':
                $temp = SessionsOnline::find($id);
                if(! $temp) return null;
                if($temp->admission) {
                    $content = __('notifcations.session-ok-from-coach') . ' ' . $temp->sessionOffer->name;
                } else {
                    $content = __('notifcations.session-not-ok-from-coach') . ' ' . $temp->sessionOffer->name . ' ' . __('notifcations.find-session-on-anouther-time');
                }
                $poster = $temp->sessionOffer->poster;
            break;
            default:
            return null;
        }
        return [
            'id' => $temp->id,
            'image' => $poster,
            'content' => $content
        ];
    }
    private function saveNotifcations($notifcations) {
        foreach($notifcations as $notifcation) {
            $data = $this->getData($notifcation->n_id, $notifcation->type);
            if($data == null) continue;
            $this->addToNotifaction (
                $data['id'],
                $data['image'],
                $notifcation->created_at,
                $data['content'],
                $notifcation->readed,
                $notifcation->type
            );
        }
    }
}
