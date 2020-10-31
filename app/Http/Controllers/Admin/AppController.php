<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppInfo;
use App\Models\Admin;
use App\Traits\AjaxResponse;
use Storage;
use Validator;
use Hash;
class AppController extends Controller
{
    use AjaxResponse;
    public function index() {
        $appInfos = AppInfo::get();
        $data = array();
        foreach($appInfos as $record) {
            $data[$record->key] = $record->value;
        }
        $appInfos = $data;
        return view('admin.settings', ['appInfos' => $appInfos]);
    }
    public function store(Request $request) {
        $data = array();
        $oldImage = AppInfo::where('key', 'about_coach_image')->first();
        if($request->hasFile('about_coach_image')) {
            if($request->file('about_coach_image')->getSize() > 0) {
                if($oldImage) Storage::disk('local')->delete($oldImage->value);
                $image = $request->file('about_coach_image')->store('/public/images/static');
                array_push($data, ['key' => 'about_coach_image', 'value' => str_replace('public', 'storage',$image)]);
            } else {
                if($oldImage) array_push($data, ['key' => 'about_coach_image', 'value' => $oldImage->value]);
            }
        } else {
            if($oldImage) array_push($data, ['key' => 'about_coach_image', 'value' => $oldImage->value]);
        }
        if($request->has('first_statment')) {
            if(str_replace(' ', '',$request->input('first_statment')) != '') {
                array_push($data, ['key' => 'first_statment', 'value' => $request->input('first_statment')]);
            }
        }
        if($request->has('about_cach_title')) {
            if(str_replace(' ', '',$request->input('about_cach_title')) != '') {
                array_push($data, ['key' => 'about_cach_title', 'value' => $request->input('about_cach_title')]);
            }
        }
        if($request->has('about_cach_desc')) {
            if(str_replace(' ', '',$request->input('about_cach_desc')) != '') {
                array_push($data, ['key' => 'about_cach_desc', 'value' => $request->input('about_cach_desc')]);
            }
        }
        AppInfo::truncate();
        if(AppInfo::insert($data)) return back()->with('msg', __('masseges.save-data-ok'));
        return back()->with('msg', __('masseges.general-error'));
    }
    public function profile() {
        $admins = Admin::all();
        return view('admin.profile', ['admins' => $admins]);
    }
    public function addAdmin(Request $request) {
        $data = [
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $rules = [
            'username' => 'required | string | unique:admins,username| min:1 | max:255',
            'email' => 'required | string | email | unique:admins,email| min:1 | max:255',
            'password' => 'string | min:8',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $this->getResponse(false, '', []);
        $data['password'] = Hash::make($data['password']);
        $admin = Admin::create($data); 
        return $this->getResponse(true, '', $admin);
    }
    public function updateAdmin(Request $request) {
        $admin = Admin::where('email', $request->input('old_email'))->first();
        $data = [
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $rules = [
            'username' => 'required | string | min:1 | max:255',
            'email' => 'required | string | email | min:1 | max:255',
            'password' => 'string | min:8',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $this->getResponse(false, '', []);
        $data['password'] = Hash::make($data['password']);
        $admin->update($data); 
        return $this->getResponse(true, '', $admin);
    }
    public function deleteAdmin($id) {
        $admin = Admin::find($id);
        if(! $admin) return $this->getResponse(false, '', []);
        $data = ['id' => $admin->id];
        $admin->delete();
        return $this->getResponse(true, '', $data);
    }
}
