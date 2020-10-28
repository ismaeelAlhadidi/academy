<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppInfo;
use Storage;

class AppController extends Controller
{
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
}
