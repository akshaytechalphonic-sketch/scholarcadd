<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Course Enrollment Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Nunito Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-family: 'Nunito', Arial, sans-serif;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .wrapper {
            width: 100%;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .header {
            padding: 35px 20px 25px;
            text-align: center;
            border-bottom: 1px solid #dddddd;
        }

        .brand-logo {
            width: 220px;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .content {
            padding: 20px 0px;
            font-size: 14px;
            line-height: 1.7;
            color: #000000;
        }

        a {
            color: #000000;
            text-decoration: underline;
        }

        .footer {
            padding: 15px 0px;
            text-align: center;
            font-size: 12px;
            color: #000000;
            border-top: 1px solid #dddddd;
        }

        .payment-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #0f0f0f;
            margin: 20px 0;
        }

        .payment-details strong {
            color: #0d0d0e;
        }
    </style>
</head>

<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="container" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="header">
                            <img src="{{ env('APP_LOGO') }}" alt="ScholarAcad Logo" class="brand-logo">
                        </td>
                    </tr>

                    <tr>
                        <td class="content">
                            <p>Hi <strong>{{ $name ?? 'User' }}</strong>,</p>

                            <p>Greetings from <strong>ScholarAcad</strong>!</p>

                            <p>
                                We are pleased to inform you that you have successfully enrolled in the course
                                <strong>{{ $course ?? 'Course Name' }}</strong>.
                            </p>

                            <div class="payment-details">
                                <p><strong>Please find your payment details below for your reference:</strong></p>
                                <p>
                                    <strong>Transaction ID:</strong> {{ $payment_Id ?? 'N/A' }}<br>
                                    <strong>Paid Amount:</strong> {{ $currency ?? 'N/A' }} {{ $paid_amount ?? 'N/A' }}<br>
                                    <strong>Payment Date:</strong> {{ $payment_date ?? 'N/A' }}<br>
                                    <strong>Payment Status:</strong> {{ $payment_status ?? 'N/A' }}<br>
                                    <strong>Plan Name:</strong> {{ $plan_name ?? 'N/A' }}<br>
                                    <strong>Schedule Date:</strong> {{ $schedule_start_date ?? 'N/A' }} to {{ $schedule_end_date ?? 'N/A' }} <br>
                                    <strong>Invoice:</strong> {{ $invoceUrl  ?? 'N/A' }}<br>

                                </p>
                            </div>

                            <p>
                                Our team will connect with you shortly to share further details regarding
                                course access, schedule, and next steps.
                            </p>

                            <p>
                                Thank you for choosing ScholarAcad. We look forward to supporting you
                                in your learning journey.
                            </p>

                            <p>
                                Please feel free to reach out to us for any queries at
                                <a style="color: blue;" href="mailto:info@scholaracad.com">
                                    info@scholaracad.com
                                </a>.
                            </p>

                            <p>
                                Best regards,<br>
                                <strong>ScholarAcad Team</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td class="footer">
                            © {{ date('Y') }}
                            <a style="text-decoration: none;" href="https://scholaracad.com/">
                                ScholarAcad
                            </a>.
                            All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
