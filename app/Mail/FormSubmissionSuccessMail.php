<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSubmissionSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $formType;

    public function __construct(string $name, string $formType)
    {
        $this->name = $name;
        $this->formType = $formType;
    }

    public function build()
    {
        return $this->subject("Your {$this->formType} submission was successful")
            ->view('email.form_success');
    }
}
