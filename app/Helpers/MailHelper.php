<?php

namespace App\Helpers;

use App\Mail\FormSubmissionSuccessMail;
use App\Mail\LearningCalenderMail;
use App\Mail\ScheduleAppointmentMail;
use App\Mail\TeamApplicationMail;
use App\Mail\PaymentDoneMail;
use Mail;
use Log;

class MailHelper
{
    public static function sendSuccessMail(?string $email, string $name, $course = null, string $formType, $data = null): void
    {
        if (!$email) {
            return;
        }

        try {
            if ($formType == "Schedule Appointment" || $formType == "Contact") {
                // dd($course);
                Mail::to($email)->cc('umesh@scholaracad.com')
                ->send(
                    new ScheduleAppointmentMail($name, $course, $formType)
                );
            } else if ($formType == "Team Application") {
                // dd($data);
                Mail::to($email)->cc('umesh@scholaracad.com')
                ->send(
                    new TeamApplicationMail($name, $course, $formType, $data)
                );
            } else if ($formType == "Learning Calendar Enquiry") {
                // dd($course);
                Mail::to($email)->cc('umesh@scholaracad.com')
                ->send(
                    new LearningCalenderMail($name, $course, $formType)
                );
            }
             else {
                Mail::to($email)->send(
                    new FormSubmissionSuccessMail($name, $formType)
                );
            }




        } catch (\Throwable $e) {
            Log::error("Mail failed for {$formType}", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
        }
    }

    public static function sendSuccessPaymentMail(
    ?string $email,
    string $name,
    ?string $course = null,
    ?string $payment_Id = null,
    ?string $paid_amount = null,
    ?string $payment_date = null,
    ?string $payment_status = null,
    ?string $currency = null,
    ?string $schedule_start_date = null,
    ?string $schedule_end_date = null,
    ?string $plan_name = null,
     ?string $invoceUrl = null,
    string $formType,

): void

    {
        if (!$email) {
            return;
        }

        try {
           if ($formType == "payment checkout") {
                // dd($name);
                Mail::to($email)->cc('umesh@scholaracad.com')
                    ->send(new PaymentDoneMail(
                        $name,$course, $payment_Id,$paid_amount,$payment_date,$payment_status,$currency,$schedule_start_date,$schedule_end_date,$plan_name ,$invoceUrl,$formType )
                    );
            }
             else {
                Mail::to($email)->send(
                    new FormSubmissionSuccessMail($name, $formType)
                );
            }




        } catch (\Throwable $e) {
            Log::error("Mail failed for {$formType}", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
        }
    }
}
