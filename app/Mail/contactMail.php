<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class contactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detail;

    /**
     * Create a new message instance.

     *

     * @return void
     */
    public function __construct($detail)
    {

        $this->detail = $detail;

    }

    /**
     * Build the message.

     *

     * @return $this
     */
    public function build()
    {

        $sub = setting_option('name-company');

        $this->from(env('MAIL_USERNAME'), setting_option('company_name'));

        return $this->subject($sub.$this->detail['subject'])

            ->view('theme.mail.contact');

    }
}
