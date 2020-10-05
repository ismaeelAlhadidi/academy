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
        $notifcations = [
            array (
                'id' => 0,
                'readed' => true,
                'content' => 'hi esmaeel',
                'image' => '/images/static/user-default.jpg',
                'time' => time(),
                'type' => 'n',
            ),
            array (
                'id' => 0,
                'readed' => true,
                'content' => 'hi esmaeel',
                'image' => '/images/static/user-default.jpg',
                'time' => time(),
                'type' => 'n',
            ),
            array (
                'id' => 0,
                'readed' => true,
                'content' => 'hi esmaeel',
                'image' => '/images/static/user-default.jpg',
                'time' => time(),
                'type' => 'n',
            ),
            array (
                'id' => 0,
                'readed' => true,
                'content' => 'hi esmaeel',
                'image' => '/images/static/user-default.jpg',
                'time' => time(),
                'type' => 'n',
            ),
            array (
                'id' => 0,
                'readed' => true,
                'content' => 'hi esmaeel',
                'image' => '/images/static/user-default.jpg',
                'time' => time(),
                'type' => 'n',
            ),
        ];
        session(['notifcations' => $notifcations]);
        $countOfPlaylistsInHomePage = 12;
        $playlists = Playlist::orderBy('id','desc')->paginate($countOfPlaylistsInHomePage);
        $playlists->transform(function ($playlist) {
            if($playlist->availability_time != null) {
                $playlist->availability_time = Date('F j, Y, g:i a',strtotime($playlist->availability_time));
            } elseif (! $playlist->available) {
                $playlist->availability_time = __('masseges.not-available');
            }
            if($playlist->price == 0) $playlist->price = null;
            return $playlist;
        });
        return view('home', ['playlists' => $playlists]);
    }
}
