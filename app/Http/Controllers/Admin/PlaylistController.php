<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\PlaylistOpinion;
use App\Models\SpecialPlaylist;
use App\Models\Subscription;
use App\Models\Comment;
use App\Models\Type;
use App\Models\Video;
use App\Models\Audio;
use App\Models\Book;
use App\Models\Blob;
use App\User;
use App\Traits\AjaxResponse;
use Illuminate\Support\Str;
use Storage;
use Validator;
use App\jobs\convertMediaToHls;

class PlaylistController extends Controller
{
    use AjaxResponse;
    public $playlist_id;
    public function index() {
        $playlists = Playlist::orderBy('id','desc')->paginate(10);
        return view('admin.playlists',['playlists' => $playlists]);
    }

    public function add() {
        $types = Type::all();
        return view('admin.playlist.add',['types' => $types]);
    }

    public function update($playlist_id) {
        $types = Type::all();
        $playlist = Playlist::find($playlist_id);
        if(! $playlist)return abort('404');

        $this->playlist_id = $playlist_id;
        $typesOfThisPlaylist = $playlist->types;

        $videos = $playlist->blobs()->where('blobable_type', '=', 'App\Models\Video')->get();
        $videos -> transform(function($blob) {
            $prefixPath = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR;
            $data = array (
                'id' => $blob->blobable_id,
                'file' => asset('blob' . DIRECTORY_SEPARATOR . 'video' . DIRECTORY_SEPARATOR . $blob->public_route),
                'type_id' => $blob->type_id,
                'playlist_id' => $this->playlist_id,
                'availability_time' => Date('Y-m-d',strtotime($blob->blobable->availability_time)),
                'poster_src' => $blob->blobable->poster_src,
                'pre_title' => $blob->blobable->pre_title,
                'title' => $blob->blobable->title,
                'size' => Storage::disk($blob->blobable->driver)->exists($prefixPath . $blob->blobable->src) ?
                    Storage::disk($blob->blobable->driver)->size($prefixPath . $blob->blobable->src) :
                    Storage::disk($blob->blobable->driver)->exists($prefixPath . $blob->blobable->src),
            );
            return $data;
        });

        $books = $playlist->blobs()->where('blobable_type', '=', 'App\Models\Book')->get();
        $books -> transform(function($blob) {
            $prefixPath = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR;
            $data = array (
                'id' => $blob->blobable_id,
                'type_id' => $blob->type_id,
                'playlist_id' => $this->playlist_id,
                'availability_time' => Date('Y-m-d',strtotime($blob->blobable->availability_time)),
                'description' => $blob->description,
                'poster_src' => $blob->blobable->poster_src,
                'pre_title' => $blob->blobable->pre_title,
                'title' => $blob->blobable->title,
                'size' => Storage::disk($blob->blobable->driver)->exists($prefixPath . $blob->blobable->src) ?
                    Storage::disk($blob->blobable->driver)->size($prefixPath . $blob->blobable->src) :
                    Storage::disk($blob->blobable->driver)->exists($prefixPath . $blob->blobable->src),
            );
            return $data;
        });

        $audios = $playlist->blobs()->where('blobable_type', '=', 'App\Models\Audio')->get();
        $audios -> transform(function($blob) {
            $prefixPath = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR;
            $data = array (
                'id' => $blob->blobable_id,
                'type_id' => $blob->type_id,
                'playlist_id' => $this->playlist_id,
                'availability_time' => Date('Y-m-d',strtotime($blob->blobable->availability_time)),
                'description' => $blob->description,
                'poster_src' => $blob->blobable->poster_src,
                'pre_title' => $blob->blobable->pre_title,
                'title' => $blob->blobable->title,
                'size' => Storage::disk($blob->blobable->driver)->exists($prefixPath . $blob->blobable->src) ?
                    Storage::disk($blob->blobable->driver)->size($prefixPath . $blob->blobable->src) :
                    Storage::disk($blob->blobable->driver)->exists($prefixPath . $blob->blobable->src),
            );
            return $data;
        });
        return view('admin.playlist.update',[
            'types' => $types,
            'playlist' => $playlist,
            'videos' => $videos,
            'audios' => $audios,
            'books' => $books,
            'typesOfThisPlaylist' => $typesOfThisPlaylist
        ]);
    }

    public function getOpinionOfThisPlaylist($playlist_id) {
        $opinions = PlaylistOpinion::where('playlist_id','=',$playlist_id)->get();
        if(! $opinions) return $this->getResponse(false,__('masseges.general-error'),null);
        $opinions->transform(function($opinion) {
            $data = array (
                'id' => $opinion->id,
                'allow' => $opinion->allow,
                'content' => $opinion->content,
                'time' => Date('F j, Y, g:i a',strtotime($opinion->created_at)),
                'user_image' => $opinion->user->image,
                'user_first_name' => $opinion->user->first_name,
            );
            return $data;
        });
        return $this->getResponse(true,'',$opinions);
    }

    public function getCommentsWithReplaysOfThisPlaylist($playlist_id) {
        $comments = Comment::where('playlist_id','=',$playlist_id)->orderBy('id','desc')->with('user')->with('replays')->get();
        if(! $comments) return $this->getResponse(false,__('masseges.general-error'),null);
        $comments->transform(function($comment) {
            $replays = collect($comment->replays);
            $replays->transform(function($replay) {
                $data = array(
                    'id' => $replay->id,
                    'allow' => $replay->allow,
                    'content' => $replay->content,
                    'time' => Date('F j, Y, g:i a',strtotime($replay->created_at)),
                    'user_image' => $replay->user->image,
                    'user_first_name' => $replay->user->first_name,
                );
                return $data;
            });
            $data = array (
                'id' => $comment->id,
                'allow' => $comment->allow,
                'content' => $comment->content,
                'time' => Date('F j, Y, g:i a',strtotime($comment->created_at)),
                'user_image' => $comment->user->image,
                'user_first_name' => $comment->user->first_name,
                'replays' => $replays,
            );
            return $data;
        });
        return $this->getResponse(true,'',$comments);
    }

    public function ToggleAvailablePlaylist($playlist_id) {
        $playlist = Playlist::find($playlist_id);
        if(! $playlist) return $this->getResponse(false,__('masseges.general-error'),[]);
        if($playlist->available) $available = false;
        else $available = true;
        if($playlist->update(['available' => $available])) return $this->getResponse(true,'',['available' => $available]);
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    }

    public function deletePlaylist($playlist_id) {
        $playlist = Playlist::find($playlist_id);
        if(! $playlist) return $this->getResponse(false,__('masseges.general-error'),[]);
        $id = $playlist->id;
        if($playlist->delete())return $this->getResponse(true,'',['id' => $id]);
        else return $this->getResponse(false,__('masseges.general-error'),[]);
    }

    public function newTypeStore(Request $request) {
        if($request->has('description')) {
            $data = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ];
        } else {
            $data = [ 'name' => $request->input('name') ];
        }
        $ruels = array(
            'name' => 'required|string|max:255|min:1',
            'description' => 'string'
        );
        $validator = Validator::make($data,$ruels);
        if ($validator->fails()) {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
        $type = Type::create($data);
        if(! $type) return $this->getResponse(false, __('masseges.general-error'),[]);
        return $this->getResponse(true, __('masseges.add-ok'),['type' => $type]);
    }
    
    public function store(Request $request) {
        $data = array();
        if($request->input('availability_time') != null) $data['availability_time'] = $request->input('availability_time');
        if($request->input('description') != null) $data['description'] = $request->input('description');
        if($request->input('price') != null) $data['price'] = $request->input('price');
        if($request->input('title') != null) $data['title'] = $request->input('title');
        if($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster');
        }
        $ruels = array (
            'title' => 'required|string|max:255|min:1',
            'description' => 'string',
            'availability_time' => 'date',
            'price' => 'numeric',
            'image' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
        );
        $validator = Validator::make($data,$ruels);
        if($validator->fails()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), null);
        }
        if($request->hasFile('poster')) {
            $ex = $request->file('poster')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid('list',true)) . '.' . $ex;
            $path = '/public/images/upload/';
            $request->file('poster')->storeAs($path,$name);
            $data['poster'] = '/storage/images/upload/' . $name;
        }
        $playlist = Playlist::create($data);
        if(! $playlist) return $this->getResponse(false, __('masseges.general-error'), null);
        if($request->has('types')) {
            $types = $request->input('types');
            if(is_array($types)) {
                if(count($types) > 0) {
                    $playlist->types()->attach($types);
                    return $this->getResponse(true, __('masseges.add-ok'), ['playlist' => $playlist]);
                } else {
                    return $this->getResponse(true, __('masseges.add-ok'), ['playlist' => $playlist]);
                }
            }
        } else {
            return $this->getResponse(true, __('masseges.add-ok'), ['playlist' => $playlist]);
        }
        return $this->getResponse(false, __('masseges.general-error'), null);
    }

    public function storeVideoInLocal(Request $request) {
        $updating = false;
        if($request->has('id')) {
            if($request->input('id') != -1) {
                $video = Video::find($request->input('id'));
                if(! $video) return $this->getResponse(false, __('masseges.general-error'), null);
                $blob = $video->blob;
                if(! $blob) return $this->getResponse(false, __('masseges.general-error'), null);
                $updating = true;
                if($request->has('poster_src')) $oldPoster = $video->poster_src;
                $oldSrc = $video->src;
                $oldDriver = $video->driver;
            }
        }
        $data = array();
        if($request->has('pre_title'))$data['pre_title'] = $request->input('pre_title');
        if($request->has('title'))$data['title'] = $request->input('title');
        if($request->has('availability_time'))$data['availability_time'] = $request->input('availability_time');
        if($request->has('poster_src'))$data['poster_src'] = $request->file('poster_src');
        if($request->has('type_id'))$data['type_id'] = $request->input('type_id');
        if($request->has('video'))$data['video'] = $request->file('video');
        if($request->has('playlist_id'))$data['playlist_id'] = $request->input('playlist_id');
        $ruels = [
            'pre_title' => ( $updating ? '' : 'required|' ) .  'string|max:255|min:1',
            'title' => ( $updating ? '' : 'required|' ) . 'string|max:255|min:1',
            'availability_time' => 'date',
            'poster_src' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
            'type_id' => 'integer',
            'video' => 'required|file|mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi',
            'playlist_id' => 'integer',
        ];
        $validator = Validator::make($data,$ruels);
        if($validator->fails()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), null);
        }

        // 1- upload video in "storage/app/private/video/playlist-id/video-time-unqid.ex" 
        $ex = $request->file('video')->getClientOriginalExtension();
        if(! $updating) {
            if($request->has('playlist_id')) {
                $path = 'playlist' . $request->playlist_id  . DIRECTORY_SEPARATOR;
            }
            else {
                $path = 'single' . DIRECTORY_SEPARATOR;
            }
        }
        else {
            if($request->has('playlist_id')) {
                $path = 'playlist' . $request->playlist_id  . DIRECTORY_SEPARATOR;
            }
            else if($video->blob->playlists->count() > 0) {
                $path = 'playlist' . $video->blob->playlists->first()->id . DIRECTORY_SEPARATOR;
            }
            else {
                $path = 'single' . DIRECTORY_SEPARATOR;
            }
        }
        $name = $this->createBlobName($ex);
        $mimiType = $request->file('video')->getMimeType();
        if(! $request->file('video')->move(storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'  . DIRECTORY_SEPARATOR . $path),$name)) {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
        $data = $request->only(['pre_title','title','availability_time']);
        $data['src'] = $path . $name;
        $data['driver'] = 'local';
        $data['mimi_type'] = $mimiType;

        // 2- upload poster in "storage/images/upload/videosPoster/unqid.ex" 
        if($request->hasFile('poster_src')) {
            $ex = $request->file('poster_src')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid('video',true))  . '.' . $ex;
            $path = '/public/images/upload/' . 'videosPoster/';
            $request->file('poster_src')->storeAs($path,$name);
            $data['poster_src'] = '/storage/images/upload/' . 'videosPoster/' . $name;
        }

        // 3- save in video table 
        if($updating) {
            if(! $video->update($data)) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'  . DIRECTORY_SEPARATOR . $data['src'], $data['driver']);
                return $this->getResponse(false, __('masseges.general-error'), null);
            } else {
                if($request->has('poster_src')) {
                    if($request->has('poster_src') != "/images/static/video-default.jpg") {
                        $this->deleteFile(str_replace('storage', 'public', $oldPoster),'local');
                    }
                }
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'  . DIRECTORY_SEPARATOR . $oldSrc, $oldDriver);
            }
        } else {
            if(! $request->has('playlist_id')) {
                $data['form_key'] = $this->generateFormKey();
            }
            $video = Video::create($data);
            if(! $video) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'  . DIRECTORY_SEPARATOR . $data['src'], $data['driver']);
                return $this->getResponse(false, __('masseges.general-error'), null);
            }
        }

        // 4- make [ public - route ] "host/video/unqid"
        $data = array();
        if($request->has("type_id")) if($request->type_id != "-1") $data['type_id'] = $request->type_id;
        $data['public_route'] = $this->createPublicRoute($video->id);
        $data['blobable_type'] = 'App\Models\Video';
        $data['blobable_id'] = $video->id;

        // 5- save in object table 
        if($updating) {
            if(! $blob->update($data)) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'  . DIRECTORY_SEPARATOR . $video->src, $video->driver);
                $video->delete();
                return $this->getResponse(false, __('masseges.general-error'), null);
            }
        } else {
            $blob = Blob::create($data);
            if(! $blob) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'video'  . DIRECTORY_SEPARATOR . $video->src, $video->driver);
                $video->delete();
                return $this->getResponse(false, __('masseges.general-error'), null);
            }
        }

        // 6- add to playlist 
        if(! $updating) {
            if($request->has('playlist_id')) {
                $blob->playlists()->attach($request->playlist_id);
            }
        }

        if(! $request->has("playlist_id")) {
            $videoData = [
                'id' => $video->id,
                'form_key' => $video->form_key,
                'pre_title' => $video->pre_title,
                'title' => $video->title,
                'availability_time' => $video->availability_time != null ? date('Y-m-d',strtotime($video->availability_time)) : $video->availability_time,
                'poster_src' => $video->poster_src,
                'type_id' => $video->blob->type_id,
                'src' => asset('blob/video') . '/' . $video->blob->public_route,
            ];
            return $this->getResponse(true, __('masseges.add-ok'), ['video' => $videoData]);
        }
        $this->dispatchConvertToHlsJob($video);
        return $this->getResponse(true, __('masseges.add-ok'), ['id' => $video->id]);
    }

    public function storeBlob(Request $request, $type) {
        if($type != "book" && $type != "audio") return $this->getResponse(false, __('masseges.general-error'), null);
        $updating = false;
        if($request->has('id')) {
            if($request->input('id') != -1) {
                $file = ( $type == "book" ) ? Book::find($request->input('id')) : Audio::find($request->input('id'));
                if(! $file) return $this->getResponse(false, __('masseges.general-error'), null);
                $blob = $file->blob;
                if(! $blob) return $this->getResponse(false, __('masseges.general-error'), null);
                $updating = true;
                if($request->has('poster_src')) $oldPoster = $file->poster_src;
                $oldSrc = $file->src;
                $oldDriver = $file->driver;
            }
        }
        $data = array();
        if($request->has('pre_title')) $data['pre_title'] = $request->input('pre_title');
        if($request->has('title')) $data['title'] = $request->input('title');
        if($request->has('availability_time')) $data['availability_time'] = $request->input('availability_time');
        if($request->has('description')) $data['description'] = $request->input('description');
        if($request->has('poster_src')) $data['poster_src'] = $request->file('poster_src');
        if($request->has('type_id')) $data['type_id'] = $request->input('type_id');
        if($request->has('blob')) $data['blob'] = $request->file('blob');
        if($request->has('playlist_id')) $data['playlist_id'] = $request->input('playlist_id');
        $ruels = [
            'pre_title' => ( $updating ? '' : 'required|' ) .  'string|max:255|min:1',
            'title' => ( $updating ? '' : 'required|' ) . 'string|max:255|min:1',
            'availability_time' => 'date',
            'poster_src' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
            'type_id' => 'integer',
            'blob' => 'required|file|' .  (( $type == "book" ) ? 
                'mimetypes:application/pdf, application/x-pdf,application/acrobat, applications/vnd.pdf, text/pdf, text/x-pdf'
                : 'mimetypes:application/octet-stream,audio/webm,audio/weba,audio/mpeg,mpga,mp3,wav,m4a'), /* edit audio types */
            'playlist_id' => 'integer',
        ];
        $validator = Validator::make($data,$ruels);
        if($validator->fails()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), null);
        }

        $ex = $request->file('blob')->getClientOriginalExtension();
        if(! $updating) {
            if($request->has('playlist_id')) {
                $path = 'playlist' . $request->playlist_id  . DIRECTORY_SEPARATOR;
            }
            else {
                $path = 'single' . DIRECTORY_SEPARATOR;
            }
        }
        else {
            if($request->has('playlist_id')) {
                $path = 'playlist' . $request->playlist_id  . DIRECTORY_SEPARATOR;
            }
            else if($file->blob->playlists->count() > 0) {
                $path = 'playlist' . $file->blob->playlists->first()->id . DIRECTORY_SEPARATOR;
            }
            else {
                $path = 'single' . DIRECTORY_SEPARATOR;
            }
        }
        $name = $this->createBlobName($ex);
        $mimiType = $request->file('blob')->getMimeType();
        if(! $request->file('blob')->move(storage_path('app' . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $type  . DIRECTORY_SEPARATOR . $path),$name)) {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
        $data = $request->only(['pre_title','title','availability_time']);
        $data['src'] = $path . $name;
        $data['driver'] = 'local';
        $data['mimi_type'] = $mimiType;

        if($request->hasFile('poster_src')) {
            $ex = $request->file('poster_src')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid($type,true))  . '.' . $ex;
            $path = '/public/images/upload/' . $type . 'sPoster/';
            $request->file('poster_src')->storeAs($path,$name);
            $data['poster_src'] = '/storage/images/upload/' . $type . 'sPoster/' . $name;
        }

        if($updating) {
            if(! $file->update($data)) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $data['src'], $data['driver']);
                return $this->getResponse(false, __('masseges.general-error'), null);
            } else {
                if($request->has('poster_src')) {
                    if($request->has('poster_src') != "/images/static/" . $type . "-default.jpg") {
                        $this->deleteFile(str_replace('storage', 'public', $oldPoster),'local');
                    }
                }
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $type  . DIRECTORY_SEPARATOR . $oldSrc, $oldDriver);
            }
        } else {
            $file = ($type == "book") ? Book::create($data) : Audio::create($data);
            if(! $file) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $type  . DIRECTORY_SEPARATOR . $data['src'], $data['driver']);
                return $this->getResponse(false, __('masseges.general-error'), null);
            }
        }

        $data = array();
        if($request->has('type_id')) $data['type_id'] = $request->type_id;
        $data['public_route'] = $this->createPublicRoute($file->id);
        $data['blobable_type'] = ($type == "book") ? 'App\Models\Book' : 'App\Models\Audio';
        $data['blobable_id'] = $file->id;

        if($updating) {
            if(! $blob->update($data)) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $type  . DIRECTORY_SEPARATOR . $file->src, $file->driver);
                $file->delete();
                return $this->getResponse(false, __('masseges.general-error'), null);
            }
        } else {
            $blob = Blob::create($data);
            if(! $blob) {
                if(isset($data['poster_src'])) $this->deleteFile($path . $name, 'local');
                $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $type  . DIRECTORY_SEPARATOR . $file->src, $file->driver);
                $file->delete();
                return $this->getResponse(false, __('masseges.general-error'), null);
            }
        }

        if(! $updating) {
            if($request->has('playlist_id')) {
                $blob->playlists()->attach($request->playlist_id);
            }
        }
        if($type == "audio") $this->dispatchConvertToHlsJob($file, "audio");
        return $this->getResponse(true, __('masseges.add-ok'), ['id' => $file->id]);
    }
    public function saveEdit(Request $request) {
        $playlist = Playlist::find($request->input('id'));
        if(! $playlist) {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
        $oldPoster = $playlist->poster;
        $data = array();
        if($request->input('availability_time') != null) $data['availability_time'] = $request->input('availability_time');
        if($request->input('description') != null) $data['description'] = $request->input('description');
        if($request->input('price') != null) $data['price'] = $request->input('price');
        if($request->input('title') != null) $data['title'] = $request->input('title');
        if($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster');
        }
        $ruels = array (
            'title' => 'required|string|max:255|min:1',
            'description' => 'string',
            'availability_time' => 'date',
            'price' => 'numeric',
            'image' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
        );
        $validator = Validator::make($data,$ruels);
        if($validator->fails()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), null);
        }
        if($request->hasFile('poster')) {
            $ex = $request->file('poster')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid('list',true)) . '.' . $ex;
            $path = '/public/images/upload/';
            $request->file('poster')->storeAs($path,$name);
            $data['poster'] = '/storage/images/upload/' . $name;
        }

        if(! $playlist->update($data)) {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
        if($request->hasFile('poster')) {
            if($oldPoster != null && $oldPoster != "/images/static/playlist-default.png") {
                $this->deleteFile(str_replace('storage', 'public', $oldPoster),'local');
            }
        }
        if($request->has('types')) {
            $types = $request->input('types');
            if(is_array($types)) {
                if(count($types) > 0) {
                    $playlist->types()->sync($types);
                    return $this->getResponse(true, __('masseges.update-ok'), ['playlist' => $playlist]);
                }
            }
        }
        $playlist->types()->sync([]);
        return $this->getResponse(true, __('masseges.update-ok'), ['playlist' => $playlist]);

        return $this->getResponse(false, __('masseges.general-error'), null);
    }
    public function updateVideoData(Request $request) {
        $video = Video::find($request->id);
        if(! $video) return $this->getResponse(false, __('masseges.general-error'), null);
        $data = array();
        if($request->has('pre_title'))$data['pre_title'] = $request->input('pre_title');
        if($request->has('title'))$data['title'] = $request->input('title');
        if($request->has('availability_time'))$data['availability_time'] = $request->input('availability_time');
        if($request->has('poster_src'))$data['poster_src'] = $request->file('poster_src');
        if($request->has('type_id'))if($request->input('type_id') != "null")$data['type_id'] = $request->input('type_id');
        $ruels = [
            'pre_title' => 'string|max:255|min:1',
            'title' => 'string|max:255|min:1',
            'availability_time' => 'date',
            'poster_src' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
            'type_id' => 'integer',
        ];
        $validator = Validator::make($data,$ruels);
        if($validator->fails()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), null);
        }
        if($request->hasFile('poster_src')) {
            $ex = $request->file('poster_src')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid('video',true))  . '.' . $ex;
            $path = '/public/images/upload/' . 'videosPoster/';
            $request->file('poster_src')->storeAs($path,$name);
            $data['poster_src'] = '/storage/images/upload/' . 'videosPoster/' . $name;
        }
        $oldPoster = $video->poster_src;
        unset($data['type_id']);
        if(! $video->update($data)) {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
        if($request->hasFile('poster_src')) {
            if($oldPoster != null && $oldPoster != "/images/static/video-default.png") {
                $this->deleteFile(str_replace('storage', 'public', $oldPoster),'local');
            }
        }
        if($request->has('type_id')) {
            if($request->input('type_id') != "null") {
                $data = array();
                $data['type_id'] = $request->input('type_id');
                $blob = $video->blob;
                if(! $blob->update($data)) {
                    return $this->getResponse(false, __('masseges.general-error'), null);
                }
            }
        }
        if($video->form_key != "null") {
            $videoData = [
                'id' => $video->id,
                'form_key' => $video->form_key,
                'pre_title' => $video->pre_title,
                'title' => $video->title,
                'availability_time' => $video->availability_time != null ? date('Y-m-d',strtotime($video->availability_time)) : $video->availability_time,
                'poster_src' => $video->poster_src,
                'type_id' => $video->blob->type_id,
                'src' => asset('blob/video') . '/' . $video->blob->public_route,
            ];
            return  $this->getResponse(true, __('masseges.update-ok'), $videoData);
        }
        return  $this->getResponse(true, __('masseges.update-ok'), null);
    }
    public function updateBlobData(Request $request, $type) {
        if($type != "book" && $type != "audio") return $this->getResponse(false, __('masseges.general-error'), null);

        $file = ($type == "book") ? Book::find($request->id) : Audio::find($request->id);
        if(! $file) return $this->getResponse(false, __('masseges.general-error'), null);

        $data = array();
        if($request->has('pre_title')) $data['pre_title'] = $request->input('pre_title');
        if($request->has('title')) $data['title'] = $request->input('title');
        if($request->has('availability_time')) $data['availability_time'] = $request->input('availability_time');
        if($request->has('description')) $data['description'] = $request->input('description');
        if($request->has('poster_src')) $data['poster_src'] = $request->file('poster_src');
        if($request->has('type_id')) if($request->input('type_id') != "null") $data['type_id'] = $request->input('type_id');
        $ruels = [
            'pre_title' => 'string|max:255|min:1',
            'title' => 'string|max:255|min:1',
            'availability_time' => 'date',
            'poster_src' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
            'type_id' => 'integer',
        ];
        $validator = Validator::make($data,$ruels);
        if($validator->fails()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), null);
        }

        if($request->hasFile('poster_src')) {
            $ex = $request->file('poster_src')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid($type, true))  . '.' . $ex;
            $path = '/public/images/upload/' . $type . 'sPoster/';
            $request->file('poster_src')->storeAs($path,$name);
            $data['poster_src'] = '/storage/images/upload/' . $type . 'sPoster/' . $name;
        }

        $oldPoster = $file->poster_src;
        unset($data['type_id']);
        if(! $file->update($data)) {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
        if($request->hasFile('poster_src')) {
            if($oldPoster != null && $oldPoster != "/images/static/" . $type . "-default.png") {
                $this->deleteFile(str_replace('storage', 'public', $oldPoster),'local');
            }
        }

        if($request->has('type_id')) {
            if($request->input('type_id') != "null") {
                $data = array();
                $data['type_id'] = $request->input('type_id');
                $blob = $file->blob;
                if(! $blob->update($data)) {
                    return $this->getResponse(false, __('masseges.general-error'), null);
                }
            }
        }
        return  $this->getResponse(true, __('masseges.update-ok'), null);
    }

    public function deleteBlob($type, $id) {
        switch($type) {
            case "book":
                $file = Book::find($id);
                break;
            case "audio":
                $file = Audio::find($id);
                break;
            case "video":
                $file = Video::find($id);
                break;
            default: return abort('404');
        }
        if(! $file) return abort('404');
        $this->deleteFile(DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . $type  . DIRECTORY_SEPARATOR . $file->src, $file->driver);
        $file->blob->delete();
        $file->delete();
        return  $this->getResponse(true, __('masseges.delete-ok'), null);
    }
    public function getTypeNameFromId($id) {
        $type = Type::find($id);
        if(! $type) return null;
        $response = [
            'name' => $type->name,
        ];
        return response()->json($response);
    }
    public function toggleSpecialList($id) {
        $specialPlaylist = SpecialPlaylist::where('playlist_id', $id)->first();
        if(! $specialPlaylist) {
            $specialPlaylist = SpecialPlaylist::create(['playlist_id' => $id]);
            if(! $specialPlaylist) return $this->getResponse(false, '', null);
            $added = true;
        } else {
            if($specialPlaylist->delete()) $added = false;
        }
        return $this->getResponse(true, '', ['id' => $id, 'added' => $added]);
    }
    public function addSubscription(Request $request) {
        if(! $request->has('mail') || ! $request->has('playlist_id')) {
            return $this->getResponse(false, __('masseges.please-input-data'), null);
        }
        $user = User::where('email', $request->input('mail'))->first();
        if(! $user) return $this->getResponse(false, __('masseges.user-not-found'), null);
        $playlist = Playlist::find($request->input('playlist_id'));
        if(! $playlist) return $this->getResponse(false, __('masseges.playlist-not-found'), null);
        $subscription = Subscription::where('playlist_id' , $request->input('playlist_id'))->where('user_id', $user->id)->first();
        if($subscription) return $this->getResponse(false, __('masseges.this-user-subscription'), null);
        $data = ['playlist_id' => $playlist->id, 'user_id' => $user->id];
        if(Subscription::create($data)) {
            return $this->getResponse(true, '', null);
        } else {
            return $this->getResponse(false, __('masseges.general-error'), null);
        }
    }

    private function createBlobName($ex) {
        return str_replace('.', '-', uniqid(Str::random(6),true) . '') . '-' . str_replace('.', '-', time() . '') . '.' . $ex;
    }

    private function createPublicRoute($id) {
        return Str::random(6) . '-' . str_replace('.', '-', time() . '') . '-' . Str::random(4) . $id . str_replace('.', '-',  uniqid(Str::random(6) . '-',true) . '');
    }

    private function deleteFile($file,$driver) {
        Storage::disk($driver)->delete($file);
    }

    private function generateFormKey() {
        return str_replace('.', '-', uniqid(Str::random(4), false) . '');
    }
    private function dispatchConvertToHlsJob($media, $type = "video") {
        $path = 'private' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $media->src;
        $pathAsArray = explode('.', $media->src);
        array_pop($pathAsArray);
        $newPath = implode($pathAsArray) . '.m3u8';
        dispatch(new convertMediaToHls($media, $path, $media->driver, $newPath));
    }
}
