<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $course;
    public string $formType;
    public mixed $data;

    public function __construct(string $name, string $course, string $formType,mixed $data)
    {
        $this->name = $name;
        $this->course = $course;
        $this->data = $data;
        $this->formType = $formType;
    }

    public function build()
    {
        // dd($this->data);
        return $this->subject("Regarding Your Interest in Joining ScholarAcad as a Trainer")
            ->view('email.teamApplication');
    }
}
