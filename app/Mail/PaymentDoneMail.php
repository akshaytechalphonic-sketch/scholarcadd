<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentDoneMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name, $course, $payment_Id, $paid_amount, $payment_date, $payment_status, $currency ,$schedule_start_date,$schedule_end_date,$plan_name ,$invoceUrl , $formType;

    public function __construct(
        string $name, string $course, string $payment_Id, string $paid_amount,
        string $payment_date, string $payment_status, string $currency,string $schedule_start_date, string $schedule_end_date, string $plan_name , string $invoceUrl , string $formType
    ) {
        $this->name = $name;
        $this->course = $course;
        $this->payment_Id = $payment_Id;
        $this->paid_amount = $paid_amount;
        $this->payment_date = $payment_date;
        $this->payment_status = $payment_status;
        $this->currency = $currency;
        $this->schedule_start_date = $schedule_start_date;
        $this->schedule_end_date = $schedule_end_date;
        $this->plan_name = $plan_name;
        $this->invoceUrl = $invoceUrl;
        $this->formType = $formType;
    }

    public function build()
    {
        return $this->subject('Course Enrollment Confirmation - ScholarAcad')
                    ->view('email.paymentDoneMail');
    }
}
