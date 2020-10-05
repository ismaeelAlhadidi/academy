<?php

namespace App\Http\Controllers\Authenticated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index($id = null) {
        if($id == null) return abort('404');
        return $id;
    }
    public function subscription($id = null) {
        if($id == null) return abort('404');
        return $id;
    }
}
