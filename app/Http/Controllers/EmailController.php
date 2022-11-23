<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;

class EmailController extends Controller
{
    public function index()
    {
        $testMailData = [
            'title' => 'Dies ist eine Test-Mail des Speiseplan Projektes',
            'body' => 'Test'
        ];

        Mail::to('ddvorak@fisi-hr.de')->send(new SendMail($testMailData));

        dd('Success! Email has been sent successfully.');
    }
}
