<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpecialPlaylist;
use App\Models\Playlist;
use App\Models\PlaylistOpinion;
use App\Models\CoachOpinion;
use App\Models\Video;
use App\Models\SingleVideoForm;
use App\Models\AppInfo;
use App\Traits\AjaxResponse;
use App\User;
use Validator;

class WelcomeController extends Controller
{
    use AjaxResponse;

    public function index () {
        $countOfSpecialPlaylistsInWelcomePage = 9;
        $playlists = SpecialPlaylist::offset(0)->limit($countOfSpecialPlaylistsInWelcomePage)->orderBy('id', 'desc')->get();
        if( $playlists) {
            $playlists->transform(function ($playlist) {
                if($playlist->availability_time != null) {
                    $playlist->availability_time = Date('F j, Y, g:i a',strtotime($playlist->availability_time));
                } elseif (! $playlist->available) {
                    $playlist->availability_time = __('masseges.not-available');
                }
                if($playlist->price == 0) $playlist->price = __('masseges.free');
                else  $playlist->price .= ' $';
                return $playlist;
            });
        }
        if(! $playlists) {
            $playlists = Playlist::offset(0)->limit($countOfSpecialPlaylistsInWelcomePage)->orderBy('id', 'desc')->get();
            $playlists->transform(function ($playlist) {
                if($playlist->availability_time != null) {
                    $playlist->availability_time = Date('F j, Y, g:i a',strtotime($playlist->availability_time));
                } elseif (! $playlist->available) {
                    $playlist->availability_time = __('masseges.not-available');
                }
                if($playlist->price == 0) $playlist->price = __('masseges.free');
                else $playlist->price .= ' $';
                return $playlist;
            });
        } else if($playlists->count() == 0) {
            $playlists = Playlist::offset(0)->limit($countOfSpecialPlaylistsInWelcomePage)->orderBy('id', 'desc')->get();
            $playlists->transform(function ($playlist) {
                if($playlist->availability_time != null) {
                    $playlist->availability_time = Date('F j, Y, g:i a',strtotime($playlist->availability_time));
                } elseif (! $playlist->available) {
                    $playlist->availability_time = __('masseges.not-available');
                }
                if($playlist->price == 0) $playlist->price = __('masseges.free');
                else  $playlist->price .= ' $';
                return $playlist;
            });
        } else if($playlists->count() < $countOfSpecialPlaylistsInWelcomePage) {
            $limitFromNormalPlaylists = $countOfSpecialPlaylistsInWelcomePage - $playlists->count();
            $playlists->transform(function ($playlist) {
                $data = $playlist->playlist;
                if($data->availability_time != null) {
                    $data->availability_time = Date('F j, Y, g:i a',strtotime($data->availability_time));
                } elseif (! $data->available) {
                    $data->availability_time = __('masseges.not-available');
                }
                if($data->price == 0) $data->price = __('masseges.free');
                else $data->price .= ' $';
                return $data;
            });
            $normalPlaylists = Playlist::offset(0)->limit($limitFromNormalPlaylists)->orderBy('id', 'desc')->get();
            $normalPlaylists->transform(function ($playlist) {
                if($playlist->availability_time != null) {
                    $playlist->availability_time = Date('F j, Y, g:i a',strtotime($playlist->availability_time));
                } elseif (! $playlist->available) {
                    $playlist->availability_time = __('masseges.not-available');
                }
                if($playlist->price == 0) $playlist->price = __('masseges.free');
                else $playlist->price .= ' $';
                return $playlist;
            });
            $playlists = $playlists->merge($normalPlaylists);
        }
        $coachOpinions = CoachOpinion::where('allow', 1)->offset(0)->limit(10)->orderBy('id', 'desc')->get();
        $appInfos = AppInfo::get();
        $data = array();
        foreach($appInfos as $record) {
            $data[$record->key] = $record->value;
        }
        $appInfos = $data;
        return view('welcome', ['playlists' => $playlists, 'coachOpinions' => $coachOpinions, 'appInfos' => $appInfos]);
    }

    public function getOpinionsOfPlaylist($id) {
        $countOfOpinionsInWelcomePage = 10;
        $opinions = PlaylistOpinion::where('playlist_id' , $id)->where('allow', true)->offset(0)->limit($countOfOpinionsInWelcomePage)->orderBy('id', 'desc')->get();
        if(! $opinions) return $this->getResponse(false,__('masseges.playlist-deleted'),[]);
        $opinions->transform(function ($opinion) {
            $data = [
                'name' => $opinion->user->first_name . ' ' . $opinion->user->last_name,
                'profileImage' => $opinion->user->image,
                'content' => $opinion->content, 
            ];
            return $data;
        });
        return $this->getResponse(true,'',$opinions);
    }

    public function getForm($key) {
        $video = Video::where('form_key', $key)->first();
        if(! $video) abort(404);
        return view('forms.singleVideoForm')->with(['video' => $video]);
    }
    public function saveForm(Request $request, $key) {
        $video = Video::where('form_key', $key)->first();
        if(! $video) abort(404);
        if($request->has('first_name')) $first_name = $request->input('first_name');
        else if(auth('web')->check()) $first_name = auth()->user()->first_name;
        else $first_name = '';
        if($request->has('last_name')) $last_name = $request->input('last_name');
        else if(auth('web')->check()) $last_name = auth()->user()->last_name;
        else $last_name = '';
        if($request->has('email')) $email = $request->input('email');
        else if(auth('web')->check()) $email = auth()->user()->email;
        else $email = '';
        $checkIfEmailFound = SingleVideoForm::where('video_id', $video->id)->where('email', $email)->first();
        if($checkIfEmailFound) return back()->withErrors(['email' => __('masseges.form-email-found')]);
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
        ];
        $data['video_id'] = $video->id;
        if(auth('web')->check()) {
            $data['user_id'] = auth()->user()->id;
        } else {
            $user = User::where('email', $email)->first();
            if($user) $data['user_id'] = $user->id;
        }
        if(session()->has('visiter')) {
            $data['visiter_id'] = session()->get('visiter');
        }
        $rules = [
            'first_name' => 'required | string | min:0 | max:255',
            'last_name' => 'required | string | min:0 | max:255',
            'email' => 'email | required | min:0 | max:255',
            'user_id' => 'numeric',
            'visiter_id' => 'numeric',
            'video_id' => 'numeric | required',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) return back()->withErrors($validator->errors());
        if(SingleVideoForm::create($data)) {
            return view('forms.thanksForm')->with(['massege' => __('masseges.thanks-for-register-in-single-video')]);
        }
        return back()->withErrors(['email' => __('masseges.general-error')]);
    }
    public function getPrivacy() {
        return view('privacy');
    }
    public function getTerms() {
        return view('terms');
    }
}
