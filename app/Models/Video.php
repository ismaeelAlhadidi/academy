<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Storage;

class Video extends Model
{
    use Notifiable;
    protected $table = 'videos';
    protected $fillable = [
        'pre_title', 'title', 'availability_time', 'src', 'driver', 'mimi_type',
        'available', 'poster_src', 'form_key', 'hls_src', 'created_at','updated_at'
    ];
    protected $hidden = ['driver', 'src'];
    public $timestamps = true;
    public static function boot()
	{
        parent::boot();
		static::deleting(function ($model) {
            $temp = explode('\\', $model->hls_src);
            if(count($temp) < 2) return;
            $driver = $model->driver;
            $src = $temp[1];
            $directory = $temp[0]; 
            $path = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'hls'. DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;
            $file = $path . $src;
            if (! Storage::disk($driver)->exists($file)) return;
            $fileHandler = fopen(storage_path('app' . $file), 'r');
            $fileContent = fread($fileHandler, Storage::disk($driver)->size($file));
            fclose($fileHandler);
            $fileContent = str_replace(["\n", "\r\n", "\R"], "\n", $fileContent);
            $arrayOfUrls = explode("\n", $fileContent);
            foreach($arrayOfUrls as $url) {
                if(substr($url, 0, 1) == "#") continue;
                $tempFile = $path . $url;
                if (! Storage::disk($driver)->exists($tempFile)) continue;
                $M3U8Handler = fopen(storage_path('app' . $tempFile), 'r');
                $M3U8Content = fread($M3U8Handler, Storage::disk($driver)->size($tempFile));
                fclose($M3U8Handler);
                $M3U8Content = str_replace(["\n", "\r\n", "\R"], "\n", $M3U8Content);
                $arrayOfM3U8Files = explode("\n", $M3U8Content);
                foreach($arrayOfM3U8Files as $tsFile) {
                    if(substr($tsFile, 0, 1) == "#") continue;
                    if(str_replace(" ", "", $tsFile) != "") {
                        $tempTs = $path . $tsFile;
                        Storage::disk($driver)->delete($tempTs);
                    }
                }
                Storage::disk($driver)->delete($tempFile);
            }
            Storage::disk($driver)->delete($file);
        });
    }
    /* make auto delete */

    public function blob() {
        return $this->morphOne('App\Models\Blob', 'blobable');
    }
    public function singleVideoForms() {
        return $this->hasMany('App\Models\SingleVideoForm','single_video_form_id','id');
    }
}
