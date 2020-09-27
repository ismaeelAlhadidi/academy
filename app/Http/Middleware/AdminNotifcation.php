<?php

namespace App\Http\Middleware;
use App\Models\SessionsOnline;
use App\Models\CoachOpinion;
use App\Models\PlaylistOpinion;
use App\Models\Comment;
use App\Models\Replay;
use App\Models\AdminNotifaction as Notifcation;
use Closure;

class AdminNotifcation
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
        $notifcations = Notifcation::orderBy('id','desc')->limit(30)->get();
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
            case 'CoachOpinion':
                $temp = CoachOpinion::find($id);
                if(! $temp) return null;
                $content = $temp->user->first_name . ' ' . __('notifcations.coach-opinoin');
            break;
            case 'PlaylistOpinion':
                $temp = PlaylistOpinion::find($id);
                if(! $temp) return null;
                $content = $temp->user->first_name . ' ' . __('notifcations.playlist-opinoin') . ' ' . $temp->playlist->title;
            break;
            case 'Comment':
                $temp = Comment::find($id);
                if(! $temp) return null;
                $content = $temp->user->first_name . ' ' . __('notifcations.comment') . ' ' . $temp->playlist->title;
            break;
            case 'Replay':
                $temp = Replay::find($id);
                if(! $temp) return null;
                $content = $temp->user->first_name . ' ' . __('notifcations.replay') . ' ' . $temp->comment->user->first_name;
            break;
            case 'SessionsOnline':
                $temp = SessionsOnline::find($id);
                if(! $temp) return null;
                $content = $temp->user->first_name . ' ' . __('notifcations.session') . ' ' . $temp->sessionOffer->name;
            break;
            default:
            return null;
        }
        return [
            'id' => $temp->id,
            'image' => $temp->user->image,
            'content' => $content
        ];
    }
    private function saveNotifcations($notifcations) {
        foreach($notifcations as $notifcation) {
            $data = $this->getData($notifcation->n_id, $notifcation->type);
            if($data == null) return;
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
