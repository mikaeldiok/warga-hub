<?php

namespace Modules\Mkstarter\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Modules\Mkstarter\Entities\Mkdum;

class NotifyRegistration extends Mailable
{
    protected $mkdum;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mkdum $mkdum)
    {
        $this->mkdum = $mkdum;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mkdum = $this->mkdum;
        return $this->view('mkstarter::email.registration-email')->with('mkdum', $mkdum)
                    ->subject('Notifikasi Pendaftaran Donatur');
    }
}
