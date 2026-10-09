<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f0ff; font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0; background-color:#f3f0ff;">
        <tr>
            <td align="center">

                <!-- Main Container -->
                <table width="520" cellpadding="0" cellspacing="0"
                    style="background-color:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

                    <!-- Logo Header -->
                    <tr>
                        <td align="center" style="padding:30px 20px; border-bottom:1px solid #eeeeee;">
                            <img src="{{ env('APP_LOGO') }}"
                                 alt="ScholarAcad Logo"
                                 width="180"
                                 style="display:block; max-width:100%; height:auto;">
                        </td>
                    </tr>

                    <!-- Title Banner -->
                    <tr>
                        <td align="center" style="background-color:#9a00ff; padding:22px 20px;">
                            <span style="color:#ffffff; font-size:22px; font-weight:700;">
                                Email Verification
                            </span>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:35px 40px; color:#333333; font-size:16px; line-height:1.6;">

                            <p style="margin:0 0 16px;">
                                Hi,
                            </p>

                            <p style="margin:0 0 16px;">
                                Greetings from <strong>ScholarAcad</strong>!
                            </p>

                            <p style="margin:0 0 24px;">
                                To complete your email verification, please use the One-Time Password (OTP) below:
                            </p>

                            <!-- OTP -->
                            <div style="text-align:center; margin:30px 0;">
                                <span style="
                                    display:inline-block;
                                    padding:16px 42px;
                                    font-size:36px;
                                    font-weight:700;
                                    letter-spacing:6px;
                                    color:#ffffff;
                                    background-color:#9a00ff;
                                    border-radius:10px;">
                                    {{ $otp }}
                                </span>
                            </div>

                            <p style="margin:0 0 24px;">
                                This OTP is valid for only <strong>2 minutes</strong>. For security reasons, please do not share
                                this code with anyone.
                            </p>

                            <p style="font-size:14px; margin:0;">
                                Warm regards,<br>
                                <strong>Team ScholarAcad</strong>
                            </p>

                            <p style="color:#666666; font-size:13px; margin:20px 0 0;">
                                If you did not request this verification, you can safely ignore this email.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="padding:18px 20px; background-color:#fafafa; color:#777777; font-size:12px; border-top:1px solid #eeeeee;">
                            &copy; {{ date('Y') }} ScholarAcad. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
