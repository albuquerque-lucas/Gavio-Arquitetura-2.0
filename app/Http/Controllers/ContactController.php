<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $details = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            Mail::to(config('mail.to.address'))->queue(new ContactMail($details));
            return redirect()->back()->with('success', 'Email enviado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao enviar email: ' . $e->getMessage());
        }
    }
}
