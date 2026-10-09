<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Regarding Your Interest in Joining ScholarAcad as a Trainer</title>
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
                                <img src="{{ env('APP_LOGO') }}" alt="ScholarAcad Logo" class="brand-logo">
                            </td>
                        </tr>

                        <tr>
                            <td class="content">
                                <p>Hi <strong>{{ $name ?? 'Trainer Name' }}</strong>,</p>

                                <p>Greetings from <strong>ScholarAcad</strong>!</p>

                                <p>
                                    Thank you for expressing your interest in joining ScholarAcad as a trainer.
                                    We appreciate your enthusiasm and are pleased to share the details based on your preferences:
                                </p>

                                <p>
                                    <strong>Area(s) of Expertise:</strong> {{ $course ?? 'Course Name' }}<br>
                                    <strong>Preferred Country Location:</strong> {{ $data?->country?->name ?? 'Country Name' }}<br>
                                    <strong>Preferred Training Delivery Mode:</strong> {{ $data?->training_delivery_mode ?? 'Training Delivery Mode' }}<br>
                                    <strong>Employment Option:</strong> {{ $data?->preferred_option ?? 'Full Time / Part Time' }}<br>
                                    <strong>Availability for Travel:</strong> {{ $data?->ready_to_travel ?? 'Yes / No' }}
                                </p>

                                <p>
                                    Our team will connect with you shortly to discuss your profile
                                    and understand your expectations in more detail.
                                </p>

                                <p>
                                    We look forward to your support and hope to build a long-term
                                    professional association with you.
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

                        {{-- <tr>
                            <td class="footer">
                                © {{ date('Y') }}
                                <a style="text-decoration: none;" href="https://scholaracad.com/">
                                    ScholarAcad
                                </a>.
                                All rights reserved.
                            </td>
                        </tr> --}}

                    </table>
                </td>
            </tr>
        </table>
    </body>

    </html>
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
