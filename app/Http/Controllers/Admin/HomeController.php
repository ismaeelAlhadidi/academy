<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visiter;
use App\Models\VisiterRoute;
use App\Models\View;
use App\Models\CoachOpinion;
use App\Models\Subscription;
use App\Models\PlaylistOpinion;
use App\Models\Comment;
use App\Models\Replay;
use App\Models\SessionsOnline;
use App\Models\SessionsOffer;
use App\Models\Video;
use App\Models\Blob;
use App\Models\UsersIp;
use App\Models\Playlist;
use App\User;
use App\Mail\SessionAdmission;
use Validator;
use Mail;
use App\Jobs\SendMailJob;
use App\Jobs\SendMailsAndNotificationToUsers;
use App\Traits\AjaxResponse;

class HomeController extends Controller
{
    use AjaxResponse;

    public function index() {
        $allVisiterCount = Visiter::all()->count();
        $visiterRoutes = VisiterRoute::pluck('count');
        $allVisitesCount = 0;
        foreach($visiterRoutes as $visiterRoute)$allVisitesCount += $visiterRoute;
        $day = 24*60*60;
        $yesterday = date('Y-m-d H:i:s',time()-$day);

        $visiterRoutes = VisiterRoute::where('updated_at' ,'>', $yesterday)->pluck('count');
        $todayVisitesCount = 0;
        foreach($visiterRoutes as $visiterRoute)$todayVisitesCount += $visiterRoute;

        $usersCount = User::all()->count();

        $views = View::pluck('count');
        $allViewsCount = 0;
        foreach($views as $view)$allViewsCount += $view;

        $views = View::where('updated_at' ,'>', $yesterday)->pluck('count');
        $todayViewsCount = 0;
        foreach($views as $view)$todayViewsCount += $view;

        $lastTwoWeekVisites = array();
        for($i = 1; $i < 15; $i++) {
            $bigTime = date('Y-m-d H:i:s',time() - (($i-1)*$day));
            $smalltime = date('Y-m-d H:i:s',time() - ($i*$day));
            $visiterRoutes = VisiterRoute::where('updated_at' ,'<=', $bigTime)
                ->where('updated_at' ,'>', $smalltime)->pluck('count');
            $lastTwoWeekVisites[$i-1] = 0;
            if(! $visiterRoutes)continue;
            foreach($visiterRoutes as $visiterRoute)$lastTwoWeekVisites[$i-1] += $visiterRoute;
        }
        
        $profitLastTwoWeek = array(2,7,1,11,5,2,4,9);
        $users = User::paginate(5);
        return view('admin.home', [
            'allVisiterCount' => $allVisiterCount,
            'allVisitesCount' => $allVisitesCount,
            'todayVisitesCount' => $todayVisitesCount,
            'usersCount' => $usersCount,
            'allViewsCount' => $allViewsCount,
            'todayViewsCount' => $todayViewsCount,
            'lastTwoWeekVisites' => $lastTwoWeekVisites,
            'profitLastTwoWeek' => $profitLastTwoWeek,
            'users' => $users,
        ]);
    }

    public function getUsers() {
        $users = User::select('id','first_name','second_name','last_name','email','created_at')
            ->orderBy('id','desc')->paginate(10);
        if(! $users) return $this->getResponse(false,__('masseges.general-error'),null);
        $users->each(function($user) {
            $user->registerTime = Date('F j, Y, g:i a',strtotime($user->created_at));
            return $user;
        });
        return $this->getResponse(true,'',$users);
    }

    public function getViewsOfUser($user_id) {
        $views = View::where('user_id','=',$user_id)->with('blob')->get();
        if(! $views) return $this->getResponse(false,__('masseges.general-error'),[]);
        $views->transform(function($view) {
            $data = array (
                'count' => $view->count,
                'poster' => $view->blob->blobable->poster_src,
                'title1' => $view->blob->blobable->pre_title,
                'title2' => $view->blob->blobable->title,
            );
            return $data;
        });
        return $this->getResponse(true,'',$views);
    }
    public function getDivicesOfUser($user_id) {
        $divices = UsersIp::where('user_id','=',$user_id)->with('visiter')->get();
        if(! $divices) return $this->getResponse(false,__('masseges.general-error'),[]);
        $divices->transform(function($divice) {
            $data = array (
                'ip_address' => $divice->visiter->ip_address,
                'mac_address' => $divice->visiter->mac_address,
                'device_data' => $divice->visiter->device_data,
            );
            return $data;
        });
        return $this->getResponse(true,'',$divices);
    }
    public function getSubscriptionsOfUser($user_id) {
        $subscriptions = Subscription::select('access','id','created_at','playlist_id')->where('user_id','=',$user_id)
            ->with('playlist')->orderBy('id','desc')->get();
        if(! $subscriptions) return $this->getResponse(false,__('masseges.general-error'),[]);
        return $this->getResponse(true,'',$subscriptions);
    }

    public function getOpinionsOfUser($user_id) {
        $coachOpinions = CoachOpinion::where('user_id','=',$user_id)->orderBy('id','desc')->get();
        $playlistOpinions = PlaylistOpinion::where('user_id','=',$user_id)->with('playlist')->orderBy('id','desc')->get();
        if(! $coachOpinions || ! $playlistOpinions ) return $this->getResponse(false,__('masseges.general-error'),[]);
        $coachOpinions->each(function($coachOpinion) {
            $coachOpinion->time = Date('F j, Y, g:i a',strtotime($coachOpinion->created_at));
            return $coachOpinion;
        });
        $playlistOpinions->each(function($playlistOpinion) {
            $playlistOpinion->time = Date('F j, Y, g:i a',strtotime($playlistOpinion->created_at));
            return $playlistOpinion;
        });
        return $this->getResponse(true,'',['coachOpinions' => $coachOpinions, 'playlistOpinions' => $playlistOpinions]);
    }

    public function getCommentsAndReplaysOfThisUser($user_id) {
        $comments = Comment::where('user_id','=',$user_id)->with('playlist')->orderBy('id','desc')->get();
        $replays = Replay::where('user_id','=',$user_id)->with('comment')->orderBy('id','desc')->get();
        if(! $comments || ! $replays ) return $this->getResponse(false,__('masseges.general-error'),[]);
        $comments->each(function($comment) {
            $comment->time = Date('F j, Y, g:i a',strtotime($comment->created_at));
            return $comment;
        });
        $replays->each(function($replay) {
            $replay->time = Date('F j, Y, g:i a',strtotime($replay->created_at));
            $replay->userOfCommentName = $replay->comment->user->first_name . ' ' . $replay->comment->user->last_name;
            return $replay;
        });
        return $this->getResponse(true,'',['comments' => $comments, 'replays' => $replays]);
    }
    public function getSessionsOnlineOfUser($user_id) {
        $sessionsOnline = SessionsOnline::where('user_id','=',$user_id)->with('sessionOffer')->orderBy('id','desc')->get();
        if(! $sessionsOnline ) return $this->getResponse(false,__('masseges.general-error'),[]);
        $sessionsOnline->transform(function($sessionOnline) {
            $data = array (
                'id' => $sessionOnline->id,
                'offerName' => $sessionOnline->sessionOffer->name,
                'offerPrice' => $sessionOnline->sessionOffer->price,
                'time' => Date('F j, Y, g:i a',strtotime($sessionOnline->time)),
                'admission' => $sessionOnline->admission,
                'taken' => $sessionOnline->taken,
            );
            return $data;
        });
        return $this->getResponse(true,'',$sessionsOnline);
    }
    public function setAdmission($session_online_id) {
        $sessionOnline = SessionsOnline::find($session_online_id);
        if(! $sessionOnline) return $this->getResponse(false,__('masseges.general-error'),[]);
        

        $header = __('mail.welcome') . ' ' . $sessionOnline->user->first_name;
        if($sessionOnline->admission) {
            $msg = __('mail.reverse-admission-1') . ' ' . $sessionOnline->sessionOffer->name . ' ' . __('mail.reverse-admission-2');
            $admission = false;
        }
        else {
            $endOfThisSession = Date('Y-m-d H:i:s',strtotime($sessionOnline->time) + ($sessionOnline->sessionOffer->duration)*60*60);

            $haveSessionInThisTime = SessionsOnline::where('id','!=',$session_online_id)->where('admission',1)->where('time','>=',$sessionOnline->time)->where('time','<',$endOfThisSession)->first();
            if($haveSessionInThisTime) return $this->getResponse(false,__('masseges.have-other-session-in-this-time'),$haveSessionInThisTime);
            
            $invalid = false;
            $maxDuartionOfSession = SessionsOffer::max('duration');
            if($maxDuartionOfSession) {
                $maxDuartionOfSession = Date('Y-m-d H:i:s',strtotime($sessionOnline->time)-($maxDuartionOfSession*60*60));
                $CheckDurationSessionInThisTime = SessionsOnline::where('id','!=',$session_online_id)->where('admission',1)->where('taken',0)->where('time','>=',$maxDuartionOfSession)->get();
                if($CheckDurationSessionInThisTime) {
                    foreach($CheckDurationSessionInThisTime as $tempSession) {
                        $endOfTempSession = Date('Y-m-d H:i:s',strtotime($tempSession->time) + ($tempSession->sessionOffer->duration)*60*60);
                        if($tempSession->time <= $sessionOnline->time  && $endOfTempSession > $sessionOnline->time) {
                            $invalid = true;
                            $haveSessionInThisTime = $tempSession;
                            break;
                        }
                    }
                }
            }
            if($invalid) return $this->getResponse(false,__('masseges.have-other-session-in-this-time'),$haveSessionInThisTime);
            $msg = __('mail.ok-admission-1') . ' ' . $sessionOnline->sessionOffer->name . ' ' . __('mail.ok-admission-2')  . ' ' . $sessionOnline->time;
            $admission = true;
        }
        if($sessionOnline->update(['admission' => $admission])) {
            dispatch(new SendMailJob($sessionOnline->user->email, new SessionAdmission($header,$msg)));
            return $this->getResponse(true,'',['admission' => $admission]);
        }
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    }
    public function toggleUserPlaylistAccess($subscription_id) {
        $subscription = Subscription::find($subscription_id);
        if(! $subscription) return $this->getResponse(false,__('masseges.general-error'),[]);
        if($subscription->access) $access = false;
        else $access = true;
        if($subscription->update(['access' => $access]))return $this->getResponse(true,'',['access' => $access]);
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    }
    public function toggleAllowCoachOpinion($coach_opinion_id) {
        $coachOpinion = CoachOpinion::find($coach_opinion_id);
        if(! $coachOpinion) return $this->getResponse(false,__('masseges.general-error'),[]);
        if($coachOpinion->allow)$allow = false;
        else $allow = true;
        if($coachOpinion->update(['allow' => $allow]))return $this->getResponse(true,'',['allow' => $allow]);
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    } 
    public function toggleAllowPlaylistOpinion($playlist_opinion_id) {
        $playlistOpinion = PlaylistOpinion::find($playlist_opinion_id);
        if(! $playlistOpinion) return $this->getResponse(false,__('masseges.general-error'),[]);
        if($playlistOpinion->allow)$allow = false;
        else $allow = true;
        if($playlistOpinion->update(['allow' => $allow]))return $this->getResponse(true,'',['allow' => $allow]);
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    }
    public function toggleAllowComment($comment_id) {
        $comment = Comment::find($comment_id);
        if(! $comment) return $this->getResponse(false,__('masseges.general-error'),[]);
        if($comment->allow)$allow = false;
        else $allow = true;
        if($comment->update(['allow' => $allow]))return $this->getResponse(true,'',['allow' => $allow]);
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    }
    public function toggleAllowReplay($replay_id) {
        $replay = Replay::find($replay_id);
        if(! $replay) return $this->getResponse(false,__('masseges.general-error'),[]);
        if($replay->allow)$allow = false;
        else $allow = true;
        if($replay->update(['allow' => $allow]))return $this->getResponse(true,'',['allow' => $allow]);
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    }
    public function getSubscription() {
        $playlists = Playlist::orderBy('id','desc')->paginate(10);
        if(! $playlists) return $this->getResponse(false,__('masseges.general-error'),null);
        $playlists->transform(function($playlist) {
            $data = array(
                'id' => $playlist->id,
                'title' => $playlist->title,
                'poster' => $playlist->poster,
                'subscriptions_count' => $playlist->subscriptions->count(),
            );
            return $data;
        });
        return $this->getResponse(true,'',$playlists);
    }
    public function getUsersOfThisPlaylist($playlist_id) {
        $users = Subscription::select('id','user_id','access')->where('playlist_id','=',$playlist_id)->with('user:id,first_name,second_name,last_name,email')->orderBy('id','desc')->paginate(10);
        if(! $users) return $this->getResponse(false,__('masseges.general-error'),null);
        return $this->getResponse(true,'',$users);
    }
    public function getVisiters() {
        $visiters = Visiter::orderBy('id','desc')->paginate(10);
        if(! $visiters) return $this->getResponse(false,__('masseges.general-error'),null);
        return $this->getResponse(true,'',$visiters);
    }
    public function mailToAllPlaylistUsers(Request $request) {
        if($request->has('sub_id')) {
            $sub = Subscription::find($request->input('sub_id'));
            if(! $sub) return $this->getResponse(false, '', []);
            $playlistId = $sub->playlist_id;
        } 
        else if($request->has('playlist_id')) $playlistId = $request->input('playlist_id');
        else return $this->getResponse(false, '', []);
        $data = $request->only(['title', 'content']);
        $rules = [
            'title' => 'required | string | min:1 | max:255',
            'content' => 'required | string | min:1 | max:5000',
        ];
        $validator = Validator::make($data,$rules);
        if($validator->fails()) return $this->getResponse(false, '', []);

        $allSub = Subscription::where('playlist_id', $playlistId)->get();
        if(! $allSub) return $this->getResponse(false, '', []);
        $allSub->transform(function ($sub) {
            $data = [
                'name' => $sub->user->first_name,
                'email' => $sub->user->email,
            ];
            return $data;
        }); 
        foreach($allSub as $user) {
            dispatch(new SendMailsAndNotificationToUsers($user['email'], $user['name'], $data['title'], $data['content']));
        }
        return $this->getResponse(true, __('masseges.send-to-playlist-users-start'), $allSub);
    }
}
