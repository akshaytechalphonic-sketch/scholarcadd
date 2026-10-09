<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

app\Mail\LearningCalenderMail.php;

class LearningCalenderMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $course;

    public string $formType;

    public function __construct(string $name, string $course, string $formType)
    {
        $this->name = $name;
        $this->course = $course;
        $this->formType = $formType;
    }


    public function build()
    {
        // dd($this->course);
        return $this->subject("Your Appointment Schedule with ScholarAcad")
            ->view('email.learningCalender');
    }
}
