<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FreeVideo extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name, $description, $videoSrc;
    public function __construct($name, $description, $videoSrc)
    {
        $this->name = $name;
        $this->description = $description;
        $this->videoSrc = $videoSrc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.freeVideo', ['name' => $this->name, 'description' => $this->description, 'videoSrc' => $this->videoSrc]);
    }
}
