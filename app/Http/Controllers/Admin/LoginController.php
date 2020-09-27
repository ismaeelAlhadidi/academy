<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;

class LoginController extends Controller
{
    public function index() {
        return view('admin.login');
    }
    public function login(Request $request) {
        $validate = Validator::make($request->only(['email','password']),$this->getLoginRules());
        if(! $validate->fails()) {
            if(Auth::guard('admin') -> attempt($request -> only('email','password'))) {
                return redirect() -> route('admin.home');
            }
        }
        session(['invaild' => true]);
        return redirect() -> route('admin.login') -> withInput($request -> only('email'));
    }

    public function logout() {
        Auth::logout();
        return redirect() -> route('admin.login');
    }

    private function getLoginRules() {
        return [
            'email'=>'required|email|max:255',
            'password'=>'required|string|min:8|max:255',
        ];
    }
}
