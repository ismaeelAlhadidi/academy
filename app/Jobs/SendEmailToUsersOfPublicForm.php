<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\FreeVideo;
use App\Models\SingleVideoForm;
use Mail;

class SendEmailToUsersOfPublicForm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $name, $description, $videoSrc, $mail, $formId;
    public function __construct($mail, $name, $description, $videoSrc, $formId)
    {
        $this->name = $name;
        $this->description = $description;
        $this->videoSrc = $videoSrc;
        $this->mail = $mail;
        $this->formId = $formId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::To($this->mail)->send(new FreeVideo($this->name, $this->description, $this->videoSrc));
    }
}
