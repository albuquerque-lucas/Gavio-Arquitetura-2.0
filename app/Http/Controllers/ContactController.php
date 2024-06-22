<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $data = $request->only('name', 'email', 'subject', 'message');

        Mail::send('emails.contact', $data, function($message) use ($data) {
            $message->to('your-email@example.com')
                    ->subject($data['subject']);
        });

        return redirect()->back()->with('success', 'Mensagem enviada com sucesso!');
    }
}
