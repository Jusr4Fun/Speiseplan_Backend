<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportRedirectMail extends Mailable
{
    use Queueable, SerializesModels;

    public $testMailData;

    public function __construct($testMailData)
    {
        $this->testMailData = $testMailData;
    }

    public function build()
    {
        return $this->subject('Support-Anfrage Speiseplan von '.$this->testMailData['email'])
        ->markdown('emails.SupportRedirectMail')
        ->with('maildata', $this->testMailData);
    }
}