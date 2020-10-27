<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\MassegeFromUs;
use Mail;

class SendMailsAndNotificationToUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $email, $name, $title, $content;
    public function __construct($email, $name, $title, $content)
    {
        $this->email = $email;
        $this->name = $name;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::To($this->email)->send(new MassegeFromUs($this->name, $this->title, $this->content));
    }
}
