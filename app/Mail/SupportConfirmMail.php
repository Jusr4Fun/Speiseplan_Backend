<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public $testMailData;

    public function __construct($testMailData)
    {
        $this->testMailData = $testMailData;
    }

    public function build()
    {
        return $this->subject('Bestätigung Support-Anfrage')
        ->markdown('emails.SupportMail')
        ->with('maildata', $this->testMailData);
    }
}