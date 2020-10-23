<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Playlist;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countOfPlaylistsInHomePage = 12;
        $playlists = Playlist::orderBy('id','desc')->paginate($countOfPlaylistsInHomePage);
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
        return view('home', ['playlists' => $playlists, 'isMylist' => false]);
    }
    public function myList() {
        $countOfPlaylistsInHomePage = 12;
        $playlists_id = auth()->user()->subscriptions->pluck('playlist_id');
        $playlists = Playlist::whereIn('id', $playlists_id)->orderBy('id','desc')->paginate($countOfPlaylistsInHomePage);
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
        return view('home', ['playlists' => $playlists, 'isMylist' => true]);
    }
}
