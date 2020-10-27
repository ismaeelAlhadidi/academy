<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MassegeFromUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name, $title, $content;
    public function __construct($name, $title, $content)
    {
        $this->name = $name;
        $this->title = $title;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.massegeFromUs', ['header' => __('mail.welcome') . ' ' . $this->name, 'title' => $this->title, 'msg' => $this->content]);
    }
}
