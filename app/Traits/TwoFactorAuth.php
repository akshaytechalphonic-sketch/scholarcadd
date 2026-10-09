<?php
namespace App\Traits;
use Illuminate\Http\Request;
use Exception;
use PragmaRX\Google2FALaravel\Google2FA;
use App\Http\Controllers\Controller;
use Auth;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
trait TwoFactorAuth
{
    // protected $google2fa;

    // public function __construct(Google2FA $google2fa)
    // {
    //     $this->getGoogle2FA() = $google2fa;
    // }

    protected $google2fa;

    // Resolve Google2FA instance when needed
    protected function getGoogle2FA()
    {
        if (!$this->google2fa) {
            $this->google2fa = app(Google2FA::class);
        }

        return $this->google2fa;
    }

    public function setup(Request $request)
    {
        $user = Auth::user();
        if ($request->has('enable_2fa') && $request->enable_2fa == 'on') {
            if (!$user->is2FAEnabled()) {
                $secret = $user->generate2FASecret();
                $user->google2fa_secret = $secret;

                $qrnew = $this->getGoogle2FA()->getQRCodeUrl(
                    config('app.name'),
                    $user->email,
                    $secret
                );

                $qrCode = new QrCode($qrnew);
                $writer = new PngWriter();
                $result = $writer->write($qrCode);

                $qrCodeImage = $result->getString();
                $qrCodeImage = 'data:image/png;base64,' . base64_encode($result->getString());

            }
            $user->two_factor_enabled = true;
            $user->save();
            return view('auth.verify-2fa', ['qrCodeUrl' => $qrCodeImage]);

        } else {
            $user->google2fa_secret = null;
            $user->two_factor_enabled = false;
            $user->save();
            return redirect('/dashboard');
        }
    }


    public function two_fa_setup($enable_2fa,$user_id)
    {
        try{
            $user = Controller::model('User')::where('id',$user_id)->first();
            if ($enable_2fa && $enable_2fa == 'on') {
                if (!$user->is2FAEnabled()) {
                    $secret = $user->generate2FASecret(); 
                    $user->google2fa_secret = $secret;
                }
                $user->two_factor_enabled = true;
                $user->save();
                return ['data'=>$user_id,'message'=>'2fa-enabled-successfully'];
            } else {
                $user->google2fa_secret = null;
                $user->two_factor_enabled = false;
                $user->save();
                return ['data'=>$user_id,'message'=>'2fa-disabled-successfully'];
            }
        }
        catch (Exception $e) {
            return ['data'=>null,'message'=> $e->getMessage()];
        }

    }


    public function showVerifyForm()
    {
        $user = Auth::user();
        //dd($user->google2fa_secret);
        $token = $this->getGoogle2FA()->getCurrentOtp($user->google2fa_secret);
        $secret =  $user->google2fa_secret;

        $qrnew = $this->getGoogle2FA()->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $qrCode = new QrCode($qrnew);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $qrCodeImage = $result->getString();
        $qrCodeImage = 'data:image/png;base64,' . base64_encode($result->getString());

        return view('loginer::auth.verify-2fa',['qrCodeUrl' => $qrCodeImage]);
    }

    public function generate_token_new()
    {
        $user = Auth::user();
        $token = $this->getGoogle2FA()->getCurrentOtp($user->google2fa_secret);
        $email_teplate_id = masterParentSingleData('EmailMaster','name','2FA Verify');
        $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate_id,[$token,$user->name]);
        Controller::helper('EmailHelper')::mail_send($user->email,$mail[0],$mail[1]);
        return true;
    }

    public function reSendToken()
    {
        $user = Auth::user();
        $token = $this->getGoogle2FA()->getCurrentOtp($user->google2fa_secret);
        $email_teplate_id = masterParentSingleData('EmailMaster','name','2FA Verify'); 
        $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate_id,[$token,$user->name]);
                Controller::helper('EmailHelper')::mail_send($user->email,$mail[0],$mail[1]);
        return response()->json(["
        status => 'Success',
        message => 'Token has been re-sent on registered email',
        "],200);
    }

    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
        ]);

        $user = Auth::user();
        if ($user->verify2FAToken($request->token)) {
            session(['2fa_verified' => true]);
            return redirect('dashboard');
        }

        return redirect()->back()->withErrors(['token' => __('common.Invalid-2FA-token-Check-MFA-again-for-token')]);
    }

}
