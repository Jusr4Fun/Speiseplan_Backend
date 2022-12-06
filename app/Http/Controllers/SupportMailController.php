<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SupportConfirmMail;
use App\Mail\SupportRedirectMail;

class SupportMailController extends Controller
{
    public function Support(Request $request)
    {
        $fields = $request->validate([
            'details'  => 'required',
            'betreff'  => 'required',
            'email'    => 'required|email',
            'user' => 'sometimes',
        ]);
     
        if(count($fields['user']) < 1) {
            $testMailData = [
                'title' => 'Bestätigung der Übersendung einer Support-Anfrage',
                'body' => $fields['details'],
                'subject' => $fields['betreff'],
                'email' => $fields['email'],
            ];
            Mail::to($fields['email'])->send(new SupportConfirmMail($testMailData));
            Mail::to('support@fisi-hr.de')->send(new SupportRedirectMail($testMailData));
        } else {
            $testMailData = [
                'title' => 'Dies ist eine Support-Mail des Speiseplan Projektes',
                'body' => $fields['details'],
                'user' => $fields['user']['name'],
                'subject' => $fields['betreff'],
                'email' => $fields['email'],
            ];
            Mail::to($fields['email'])->send(new SupportConfirmMail($testMailData));
            Mail::to('support@fisi-hr.de')->send(new SupportRedirectMail($testMailData));
        }
    }

    public function SupportAuth()
    {
        $testMailData = [
            'title' => 'Dies ist eine Test-Mail des Speiseplan Projektes',
            'body' => 'Test'
        ];

        Mail::to('ddvorak@fisi-hr.de')->send(new SupportConfirmMail($testMailData));
    }
}