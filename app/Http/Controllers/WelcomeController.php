<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpecialPlaylist;
use App\Models\Playlist;
use App\Models\PlaylistOpinion;
use App\Models\CoachOpinion;
use App\Traits\AjaxResponse;

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
        return view('welcome', ['playlists' => $playlists, 'coachOpinions' => $coachOpinions]);
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
}
