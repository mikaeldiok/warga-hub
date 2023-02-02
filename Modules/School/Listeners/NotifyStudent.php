<?php

namespace Modules\School\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Modules\School\Entities\Student;
use Modules\School\Jobs\SendRegMail;

class NotifyStudent
{
    public $student;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->student = $event->student;
        
        $details['email'] = $this->student->student_email;
        
        dispatch(new SendRegMail($details, $this->student));
    }
}
