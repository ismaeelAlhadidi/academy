<?php

namespace App\Http\Controllers\Authenticated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AjaxResponse;
use App\Models\Playlist;
use App\Models\PlaylistOpinion;
use App\Models\CoachOpinion;
use App\Models\Comment;
use App\Models\Replay;
use App\User;
use Validator;
use Storage;

class UserController extends Controller
{
    use AjaxResponse;
    public function index($id = null) {
        if($id == null) { 
            $user = auth()->user();
            $haveThisProfile = true;
        }
        else {
            $user = User::find($id);
            if($id != auth()->user()->id) $haveThisProfile = false;
            else $haveThisProfile = true;
        }
        if(! $user) abort('404');
        return view('authenticated.profile',['user' => $user, 'haveThisProfile' => $haveThisProfile]);
    }
    public function saveChanges(Request $request) {
        $data = $request->only('first_name', 'second_name', 'last_name', 'email');
        $rules = [
            'first_name' => 'required|string|min:1|max:256',
            'second_name' => 'required|string|min:1|max:256',
            'last_name' => 'required|string|min:1|max:256',
            'email' => 'required|email|unique:users',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) return $this->getResponse(false, 'invalid', null);

        $user = User::find(auth()->user()->id);
        if(! $user) return $this->getResponse(false, '', null);
        if($user->update($data)) {
            return $this->getResponse(true, __('masseges.update-ok'), null);
        }
        if(! $user) return $this->getResponse(false, '', null);
    }
    public function changeImage(Request $request) {
        if(! $request->hasfile('image')) return $this->getResponse(false, '', null);
        $data = [ 'image' => $request->file('image') ];
        $rules = [
            'image' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
        ];
        $validator = Validator::make($data, $rules);
        if($validator->fails()) {
            return $this->getResponse(false, '', null);
        }
        $user = User::find(auth()->user()->id);
        if(! $user) return $this->getResponse(false, '', null);
        $oldImage = $user->image;
        $path = $request->file('image')->store('public/users');
        $data['image'] = '/' . str_replace('public/', 'storage/', $path);
        if($user->update($data)) {
            if($oldImage != '/images/static/user-default.jpg') {
                Storage::disk('local')->delete(str_replace('storage/', 'public/', $oldImage));
            }
            return $this->getResponse(true, __('masseges.update-ok'), asset($data['image']));
        }
        return $this->getResponse(false, '', null);
    }
    public function postOpinionOfPlaylist(Request $request, $id) {
        $playlist = Playlist::find($id);
        if(! $playlist) return abort(404);
        if(! $request->has('content')) return $this->getResponse(false, '', null);
        $r = [
            'content' => 'required|string|min:1|max:5000',
        ];
        $data = array (
            'playlist_id' => $playlist->id,
            'user_id' => auth()->user()->id,
            'content' => $request->input('content'),
        );
        $validator = Validator::make($data, $r);
        if($validator->fails()) {
            return $this->getResponse(false, '', null);
        }
        if(PlaylistOpinion::create($data)) return $this->getResponse(true, '', null);
        return $this->getResponse(false, '', null);
    }
    public function postOpinionOfCoach(Request $request) {
        if(! $request->has('content')) return $this->getResponse(false, '', null);
        $r = [
            'content' => 'required|string|min:1|max:5000',
        ];
        $data = array (
            'user_id' => auth()->user()->id,
            'content' => $request->input('content'),
        );
        $validator = Validator::make($data, $r);
        if($validator->fails()) {
            return $this->getResponse(false, '', null);
        }
        if(CoachOpinion::create($data)) return $this->getResponse(true, '', null);
        return $this->getResponse(false, '', null);
    }
    public function postComment(Request $request, $id) {
        $playlist = Playlist::find($id);
        if(! $playlist) return abort(404);
        if(! $request->has('content')) return $this->getResponse(false, '', null);
        $r = [
            'content' => 'required|string|min:1|max:5000',
        ];
        $data = array (
            'playlist_id' => $playlist->id,
            'user_id' => auth()->user()->id,
            'content' => $request->input('content'),
        );
        $validator = Validator::make($data, $r);
        if($validator->fails()) {
            return $this->getResponse(false, '', null);
        }
        $comment = Comment::create($data);
        if($comment) {
            $data = [
                'content' => $comment->content,
                'id' => $comment->id,
            ];
            return $this->getResponse(true, '', $data);
        }
        return $this->getResponse(false, '', null);
    }
    public function postReplay(Request $request, $id) {
        $comment = Comment::find($id);
        if(! $comment) return abort(404);
        if(! $request->has('content')) return $this->getResponse(false, '', null);
        $r = [
            'content' => 'required|string|min:1|max:5000',
        ];
        $data = array (
            'comment_id' => $comment->id,
            'user_id' => auth()->user()->id,
            'content' => $request->input('content'),
        );
        $validator = Validator::make($data, $r);
        if($validator->fails()) {
            return $this->getResponse(false, '', null);
        }
        $replay = Replay::create($data);
        if($replay) {
            $data = [
                'content' => $replay->content,
                'id' => $replay->id,
            ];
            return $this->getResponse(true, '', $data);
        }
        return $this->getResponse(false, '', null);
    }
}
