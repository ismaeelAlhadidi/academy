<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\SingleVideoForm;
use App\Jobs\SendEmailToUsersOfPublicForm;

class CheckSingleVideoForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'singlevideo:forms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command check all available single video and make jobs to send emails for all registers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $videos = Video::select('id')->where('form_key', '!=', "null")->where('availability_time', '<=', Date('Y-m-d H:i:s',time()))->get();
        if(! $videos) return 0;
        if($videos->count() < 1) return 0;
        $videos_id = $videos->pluck('id');
        for($i = 0; $i < count($videos_id); $i++) {
            $registers = SingleVideoForm::where('video_id', $videos_id[$i])->where('send_mail', 0)->get();
            foreach($registers as $register) {
                $mail = $register->email;
                $name = $register->first_name . ' ' . $register->last_name;
                $description = '';
                $videoSrc = asset('single/video/' . $register->video->blob->public_route);
                $formId = $register->id;
                dispatch(new SendEmailToUsersOfPublicForm($mail, $name, $description, $videoSrc, $formId));
                $form = SingleVideoForm::find($this->formId);
                if($form) $form->update(['send_mail' => 1]);
            }
        }
        return 0;
    }
}
