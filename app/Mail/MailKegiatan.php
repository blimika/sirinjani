<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailKegiatan extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $objEmail;

    public function __construct($objEmail)
    {
        //
        $this->objEmail = $objEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@sirinjani.bpsntb.id','SiRinjani')
                    ->subject('[NOREPLY] Ada Kegiatan Baru dari '.$this->objEmail->keg_sm)
                    ->view('kegiatan.mailkeg');
    }
}
