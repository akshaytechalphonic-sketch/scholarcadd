<?php

namespace Modules\Loginer\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Loginer\Services\LoginerService;
use Modules\Loginer\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Traits\TwoFactorAuth;
use Illuminate\View\View;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Validation\Rules\Password as RulesPassword;
use App\Mail\UserForgotPasswordMail;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Support\Str;



class LoginerController extends Controller
{
    use TwoFactorAuth;
    protected LoginerService $service;

    public function __construct(LoginerService $service)
    {
        $this->service = $service;
        //$this->middleware('auth'); // user must be logged in
    }

    protected function login()
    {
        return $this->service->login();
    }

    protected function login_check(LoginRequest $request)
    {
        return $this->service->login_check($request);
    }

    protected function dashboard()
    {
        return $this->service->dashboard();
    }
    protected function logout(Request $request)
    {
        return $this->service->logout($request);
    }
    // protected function languageSwitch(Request $request)
    // {
    //     return $this->service->languageSwitch($request);
    // }
    protected function sign_up()
    {
        return view('loginer::auth.sign-up');
    }
    protected function registrationRequest_Store(Request $request)
    {
       return $this->service->registrationRequest($request);
    }
    protected function registrationRequest_otpStore(Request $request)
    {
        return $this->service->registrationRequestOtpStore($request);
    }
    protected function registrationRequest_StoreFinal(Request $request)
    {
        return $this->service->registrationRequestStoreFinal($request);
    }


    public function create(): View
    {
        return view('loginer::auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */


        public function store(Request $request): RedirectResponse
        {
            $request->validate([
                'email' => ['required', 'email'],
            ]);
           
            $userdata = Controller::model('User', 'Loginer');
            $user =  $userdata::where('email', $request->email)
                        ->where('status', 'Active')
                        ->first();
           
            if (!$user) {
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => "Email doesn't exist"]);
            }

            $status = Password::sendResetLink([
                'email' => $user->email
            ]);
            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', __('Password reset link sent to your email.'))
                : back()->withErrors(['email' => __($status)]);
        }

    public function create_password(Request $request): View
    {
      
         return view('loginer::auth.reset-password', ['request' => $request]);
        //return view('auth.reset-password', );
    }

    public function passwordstore(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                RulesPassword::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->remember_token = Str::random(60);
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password reset successfully.')
            : back()->withErrors(['email' => __($status)]);
    }





}
