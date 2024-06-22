<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'))
                    ->to(config('mail.to.address'))
                    ->subject($this->details['subject'] ?? 'No subject')
                    ->markdown('emails.contact')
                    ->with('details', $this->details);
    }
}
