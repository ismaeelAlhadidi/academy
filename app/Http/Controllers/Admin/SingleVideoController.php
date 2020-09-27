<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Type;
class SingleVideoController extends Controller
{
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
        //return response()->json($videos);
        return view('admin.singleVideos', [ 'videos' => $videos, 'types' => $types ]);
    }
    public function getUsersData($id) {
        /* get data from database */
    }
}
