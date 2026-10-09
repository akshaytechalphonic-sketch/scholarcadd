<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Appointment Schedule with ScholarAcad</title>
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
    </style>
</head>

<body>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="container" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="header">
                            <img src="{{ env('APP_LOGO') }}" alt="Scholaracad Logo" class="brand-logo">
                        </td>
                    </tr>

                    <tr>
                        <td class="content">
                            <p>Dear <strong>{{ $name ?? 'User' }}</strong>,</p>

                            <p>Greetings from <strong>ScholarAcad</strong>!</p>

                            <p>
                                Thank you for contacting ScholarAcad to schedule an
                                appointment with us for the course
                                <strong>{{ $course ?? 'Course Name' }}</strong>.
                            </p>

                            <p>
                                Our team will connect with you shortly to understand
                                more about the requested course and guide you
                                accordingly.
                            </p>

                            <p>
                                We look forward to supporting you in your learning
                                journey.
                            </p>

                            <p>
                                Please feel free to revert for any query at
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
