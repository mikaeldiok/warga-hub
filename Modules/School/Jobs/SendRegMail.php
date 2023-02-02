<?php

namespace Modules\School\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\School\Emails\NotifyRegistration;
use Mail;

use Modules\School\Entities\Student;

class SendRegMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
   
    protected $details;
    protected $student;
   
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details,Student $student)
    {
        $this->details = $details;
        $this->student = $student;
    }
   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new NotifyRegistration($this->student);
        Mail::to($this->details['email'])->send($email);
    }
}
