<?php

namespace Modules\System\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Modules\System\Entities\Appsite;

class NotifyRegistration extends Mailable
{
    protected $appsite;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appsite $appsite)
    {
        $this->appsite = $appsite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $appsite = $this->appsite;
        return $this->view('system::email.registration-email')->with('appsite', $appsite)
                    ->subject('Notifikasi Pendaftaran Donatur');
    }
}
