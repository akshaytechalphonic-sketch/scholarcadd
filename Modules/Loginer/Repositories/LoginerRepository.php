<?php

namespace  Modules\Loginer\Repositories;

use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helpers;
use Illuminate\Http\Request;
use Modules\Loginer\Repositories\RolePermissionRepository;

class LoginerRepository
{


    public function login(): Collection
    {
        return Controller::model('User')::all();
    }

    public function loginHistory($user)
    {
        $model = Controller::model('LoginHistory', 'Loginer');
        $history = new $model();
        $history->user_id = $user->id;
        $history->login_time = Carbon::now();
        $history->save();
    }
    public function registrationRequest($request)
    {
        try {
            $model = Controller::model('RegistartionRequest', 'Loginer');
            $duplicy_email = $model::where('email', $request->email)->first();
            $login_attempt = Controller::model('SystemControl')::orderBy('id', 'desc')->first();

            if ($duplicy_email?->no_of_attempt && ($login_attempt->number_of_otp_attempt <= $duplicy_email?->no_of_attempt && $login_attempt?->otp_enable == 1)) {
                $duplicy_email->status = "Blocked";
                $duplicy_email->message = "OTP attempt limit reached..!!. You have entered wrong OTP multiple time.";
                $duplicy_email->save();
                Controller::db()::rollback();
                return 203;
            }
            if ($duplicy_email !== null) {
                if ($duplicy_email) {
                    $random = rand(100000, 999999);
                    $duplicy_email->email = $request->email;
                    $duplicy_email->name = $request->fullName;
                    $duplicy_email->otp = $random;
                    $duplicy_email->no_of_attempt += 1;
                    $duplicy_email->status = "Sent";
                    $duplicy_email->save();
                    $email_teplate_id = Controller::helper('EmailHelper')::email_template_id('OTP For Seller Registration Request');
                    $mail = Controller::helper('EmailHelper')::email_variables_loginotp($email_teplate_id, [$random, $duplicy_email->name]);
                    Controller::helper('EmailHelper')::mail_send($duplicy_email->email, $mail[0], $mail[1]);
                    return 200;
                }
            } else {
                $max_id = Controller::helper('NumberSequenceHelpers')::applysequence_regitration('Registration Registration');
                if ($max_id == 0 || $max_id == 1) {
                    Controller::db()::rollback();
                    return back()->with('error', 'Number Sequence Error!');
                }
                $random = rand(100000, 999999);
                $data = new $model();
                $data->code = $max_id;
                $data->email = $request->email;
                $data->name = $request->fullName;
                $data->otp = $random;
                $data->no_of_attempt += 1;
                $data->status = "Sent";
                $data->save();
                $email_teplate_id = Controller::helper('EmailHelper')::email_template_id('OTP For Seller Registration Request');
                $mail = Controller::helper('EmailHelper')::email_variables_loginotp($email_teplate_id, [$random, $data->name]);
                Controller::helper('EmailHelper')::mail_send($data->email, $mail[0], $mail[1]);
                return 200;
            }
        } catch (\Exception $e) {
            return 202;
        }
    }
    public function registrationRequestOtpStore($request)
    {
        // dd($request->all());
        try {
            // Validate the input fields
            $request->validate([
                'otpfield1' => 'required|numeric|digits_between:1,1',
                'otpfield2' => 'required|numeric|digits_between:1,1',
                'otpfield3' => 'required|numeric|digits_between:1,1',
                'otpfield4' => 'required|numeric|digits_between:1,1',
                'otpfield5' => 'required|numeric|digits_between:1,1',
                'otpfield6' => 'required|numeric|digits_between:1,1',
                'email' => 'required|email',
            ]);


            // Concatenate the OTP fields
            $otp = implode('', [
                $request->otpfield1,
                $request->otpfield2,
                $request->otpfield3,
                $request->otpfield4,
                $request->otpfield5,
                $request->otpfield6,
            ]);

            // Retrieve the SupplierOTPRequest for the given email
            $model = Controller::model('RegistartionRequest', 'Loginer');
            $supplierOtpRequest = $model::where('email', $request->email)->first();

            if (!$supplierOtpRequest) {
                return response()->json(['message' => 'Invalid email or OTP'], 404);
            }

            // Check if the OTP matches
            if ($supplierOtpRequest->otp == $otp) {
                $supplierOtpRequest->status = "Otp Matched";
                $supplierOtpRequest->verify_email = "verifyed";
                $supplierOtpRequest->save();
                return 200;
            }

            // Handle invalid OTP logic
            $systemAdminSetup = Controller::model('SystemControl')::orderBy('id', 'desc')->first();
            $resendAttemptLimit = $systemAdminSetup?->number_of_otp_attempt ?? 0;

            if (
                $supplierOtpRequest->no_of_attempt >= $resendAttemptLimit ||
                $supplierOtpRequest->resend_otp >= $resendAttemptLimit
            ) {
                // Block the user
                $supplierOtpRequest->status = "Blocked";
                $supplierOtpRequest->message = "OTP attempt limit reached. You have entered the wrong OTP multiple times.";
                $supplierOtpRequest->save();

                // Return a specific HTTP status code or message
                return 203; // 429 Too Many Requests is more appropriate for rate-limiting
            }

            // Increment the attempt count for invalid OTP
            $supplierOtpRequest->status = "Not Match";
            $supplierOtpRequest->no_of_attempt = ($supplierOtpRequest->no_of_attempt ?? 0) + 1;
            $supplierOtpRequest->save();

            return 201;
        } catch (\Exception $e) {
            return 202;
        }
    }

    public function vendor_otp_resend($request)
    {
        $company_id = Controller::helper('ModelHelper')::default_company_id();
        $company_id = [$company_id];
        $login_attempt = Controller::model('SystemAdminSetup')::orderBy('id', 'desc')->first();
        $random = rand(100000, 999999);
        $invite = Controller::model('SupplierOTPRequest')::where('email', $request->email)->first();
        $invite->otp =  $random;
        $invite->status = "Re send OTP";
        $invite->resend_attempt = $invite->resend_attempt + 1;
        $invite->save();
        if ($login_attempt->numbertime <= $invite?->resend_attempt && $login_attempt?->number_enable == 1) {
            $invite->status = "Blocked";
            $invite->message = "OTP attempt limit reached..!!. You have entered wrong OTP multiple time.";
            $invite->save();
            return 203;
        } else {
            $company = Controller::helper('EmailHelper')::company_name();
            $email_teplate_id = Controller::helper('EmailHelper')::email_template_id('OTP For Supplier Registration Request');
            $mail = Controller::helper('EmailHelper')::email_variables_loginotp($email_teplate_id, [$company, $random, $invite->name]);
            Controller::helper('EmailHelper')::mail_send($invite->email, $mail[0], $mail[1]);
            return 200;
        }
    }
    public function registrationRequestStoreFinal($request)
    {

        try {
            $model = Controller::model('RegistartionRequest', 'Loginer');
            $data_store = $model::where('email', $request->email)->first();

            if ($data_store) {
                $data_store->type = $request->type;
                $data_store->mobile_no = $request->mobile_no;
                $data_store->name = $request->fullname;
                $data_store->save();

                $pass = Controller::helper('CommonHelper')::generate_password();
                $userRegister = Controller::helper('CommonHelper')::create_user($data_store?->name, $data_store?->email, null, $pass, $data_store?->type);
                if ($userRegister) {
                        $request = new Request();
                        $request->merge([
                            'user_id'   => $userRegister->id,
                            'role' => $userRegister->type,
                        ]);
                       RolePermissionRepository::assignRoleToUser_new($request);
                    }

                    $email_teplate = masterParentSingleData('EmailMaster', 'name', 'OTP For Creating User');
                    $mail = Controller::helper('EmailHelper')::email_variables_replace($email_teplate?->id, [$data_store?->code, $data_store?->email, $pass, $data_store?->name]);
                    if ($mail[0]) {
                        Controller::helper('EmailHelper')::mail_send($data_store?->email, $mail[0], $mail[1]);
                    } else {
                        Controller::db()::rollback();
                        return ['status' => false, 'message' => $mail[1]];
                    }

                    //  DB::commit();
                    return back()->with(['success' => 'Your registration request is successfully submitted!']);
                } else {
                    //DB::rollBack();
                    return back()->with(['error' => 'common.customer-register-failed']);
                }

        } catch (\Exception $e) {
            return back()->with(['error' => 'common.customer-register-failed']);
        }
    }
}
