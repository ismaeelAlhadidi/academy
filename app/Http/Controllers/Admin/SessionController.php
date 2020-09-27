<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SessionsOnline;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index() {
        $sessions = SessionsOnline::orderBy('id', 'desc')->paginate(10);
        return view('admin.sessionsOnline', ['sessions' => $sessions]);
    }
}
