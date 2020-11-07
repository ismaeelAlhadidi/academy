<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;
use App\Models\Video;
use FFMpeg;
use FFMpeg\Format\Video\X264;

class convertMediaToHls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $media, $path, $driver, $newPath;
    public function __construct($media, $path, $driver, $newPath)
    {
        $this->path = $path;
        $this->driver = $driver;
        $this->newPath = $newPath;
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newSrc = DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'hls'. DIRECTORY_SEPARATOR . $this->newPath;
        if($this->media->blob->blobable_type == "App\Models\Video") {
            $lowBitrate = (new X264)->setKiloBitrate(250)->setAudioCodec("libmp3lame");
            $midBitrate = (new X264)->setKiloBitrate(500)->setAudioCodec("libmp3lame");
            $highBitrate = (new X264)->setKiloBitrate(1000)->setAudioCodec("libmp3lame");
            FFMpeg::fromDisk($this->driver)
                ->open($this->path)
                ->exportForHLS()
                ->toDisk($this->driver)
                ->addFormat($lowBitrate)
                ->addFormat($midBitrate)
                ->addFormat($highBitrate)
                ->save($newSrc);
        } else {
            FFMpeg::fromDisk($this->driver)
            ->open($this->path)
            ->exportForHLS()
            ->setAudioCodec("libmp3lame")
            ->toDisk($this->driver)->save($newSrc);
        }
        FFMpeg::cleanupTemporaryFiles();
        $this->media->update(['hls_src' => $this->newPath]);
    }
}
