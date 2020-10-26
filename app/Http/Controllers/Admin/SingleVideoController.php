<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Type;
use App\Models\SingleVideoForm;
use App\Traits\AjaxResponse;
use App\Traits\FormatTime;

class SingleVideoController extends Controller
{
    use AjaxResponse, FormatTime;
    public function index () {
        $videos = Video::where('form_key', '!=', 'NULL')->orderBy('id', 'desc')->paginate(5);
        $videos->transform(function($video) {
            $data = [
                'id' => $video->id,
                'form_key' => $video->form_key,
                'pre_title' => $video->pre_title,
                'title' => $video->title,
                'availability_time' => $video->availability_time != null ? date('Y-m-d',strtotime($video->availability_time)) : $video->availability_time,
                'poster_src' => $video->poster_src,
                'type_id' => $video->blob->type_id,
                'src' => asset('blob/video') . '/' . $video->blob->public_route,
            ];
            return collect($data);
        });
        $types = Type::select('id', 'name')->get();
        return view('admin.singleVideos', [ 'videos' => $videos, 'types' => $types ]);
    }
    public function getUsersData($id) {
        $forms = SingleVideoForm::where('video_id', $id)->paginate(6);
        if(! $forms) return $this->getResponse(false, '', null);
        if($forms->count() <= 0) return $this->getResponse(true, __('masseges.empty-users'), []);
        $forms->transform(function ($form) {
            if($form->user_id != null) {
                if($form->email == null) $form->email = $form->user->email;
                if($form->first_name == null) $form->first_name = $form->user->first_name;
                if($form->last_name == null) $form->last_name = $form->user->last_name;
            }
            $data = array (
                'email' => $form->email,
                'name' => $form->first_name . ' ' . $form->last_name,
                'timeOfFullForm' => $this->convertToReadableTime($form->created_at),
                'isUser' => ($form->user_id != null) ? __('masseges.user-in-website') : __('masseges.not-user-in-website'),
                'send_mail' => ($form->send_mail) ? __('masseges.we-send-mail') : __('masseges.we-do-not-send-mail'),
            );
            return $data;
        });
        return $this->getResponse(true, '', $forms);
    }
}
