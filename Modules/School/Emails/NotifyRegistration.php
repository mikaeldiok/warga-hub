<?php

namespace Modules\School\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Modules\School\Entities\Student;

class NotifyRegistration extends Mailable
{
    protected $student;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $student = $this->student;
        return $this->view('school::email.registration-email')->with('student', $student)
                    ->subject('Notifikasi Pendaftaran Donatur');
    }
}
