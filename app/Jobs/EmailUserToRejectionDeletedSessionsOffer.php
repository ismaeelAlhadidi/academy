<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SessionAdmission;
use Mail;

class EmailUserToRejectionDeletedSessionsOffer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $userName, $offerName, $mail;
    public function __construct($userName, $offerName, $mail)
    {
        $this->userName = $userName;
        $this->offerName = $offerName;
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $header = __('mail.welcome') . ' ' . $this->userName;
        $msg = __('mail.reverse-admission-1') . ' ' . $this->offerName . ' ' . __('mail.reverse-admission-2');
        Mail::To($this->mail)->send(new SessionAdmission($header, $msg));
    }
}
