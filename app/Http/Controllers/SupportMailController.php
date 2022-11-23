<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendMail;

class SupportMailController extends Controller
{
    public function Support(Request $request)
    {
        $fields = $request->validate([
            'details'  => 'required',
            'betreff'  => 'required',
            'email'    => 'required|email',
        ]);
        $testMailData = [
            'title' => 'Dies ist eine Support-Mail des Speiseplan Projektes',
            'body' => $fields['details'],
            'subject' => $fields['betreff']
        ];

        Mail::to($fields['email'])->send(new SendMail($testMailData));
    }

    public function SupportAuth()
    {
        $testMailData = [
            'title' => 'Dies ist eine Test-Mail des Speiseplan Projektes',
            'body' => 'Test'
        ];

        Mail::to('ddvorak@fisi-hr.de')->send(new SendMail($testMailData));
    }
}