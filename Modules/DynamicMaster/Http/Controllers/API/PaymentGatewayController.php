<?php

namespace Modules\DynamicMaster\Http\Controllers\API;

use App\Helpers\Ccavenue;
use App\Http\Controllers\Controller;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Lcobucci\JWT\Token\Plain;
use Log;
use Modules\Course\Models\CourseCountry;
use Modules\Course\Models\PromoCode;
use Modules\DynamicMaster\Models\PlanMapping;
use Modules\DynamicMaster\Models\Plan;
use Modules\Course\Models\CourseSchedule;
use Modules\Course\Models\Course;
use App\Models\Enrollment;
use App\Helpers\MailHelper;
use Modules\DynamicMaster\Http\Controllers\API\ApiController;
use Razorpay\Api\Api;
use Carbon\Carbon;

class PaymentGatewayController extends Controller
{
    public static function createRazorPayOrder(Request $request)
    {
        try {
            $request->validate([
                'currency' => 'required|string|size:3',
                'plain_id' => ' nullable|int|exists: tbl_plan_mappings,id',
                'quantity' => 'required|int'
            ]);

            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );


            $amount = self::calculateAmount($request);

            if ($amount <= 0) {
                throw new \Exception('Invalid amount calculated.');
            }

            $order = $api->order->create([
                'receipt' => 'order_rcpt_' . time(),
                'amount' => $amount * 100,
                'currency' => $request->currency,
                'payment_capture' => 1,
            ]);

            return response()->json([
                'success' => true,
                'payment_id' => $order['receipt'],
                'order_id' => $order['id'],
                'amount' => $amount,
                'key' => config('services.razorpay.key'),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Razorpay\Api\Errors\BadRequestError $e) {
            Log::error('Razorpay Order Error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error',
                'errors' => $e->getMessage(),
            ], 500);

        } catch (\Exception $e) {
            Log::error('Create Order Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);


            $errorMessage = app()->environment('local', 'development')
                ? $e->getMessage()
                : 'Something went wrong';

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
            ], 500);
        }

    }


    public function verifyRazorPayPayment(Request $request)
    {
        try {


            $request->validate([
                'razorpay_order_id' => 'required|string',
                'razorpay_payment_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);


            $api = new Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,

            ];




            $api->utility->verifyPaymentSignature($attributes);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully',
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            Log::warning('Razorpay Signature Mismatch', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid payment signature',

            ], 400);

        } catch (Exception $e) {
            Log::error('Payment Verification Error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed',
            ], 500);
        }
    }


    /**
     * ==================================
     * CCAvenue Payment Gateway
     * ==================================
     */

    public function ccAvenueCreatePayment(Request $request)
    {
        $amount = self::calculateAmount($request);
        $enrollment = Enrollment::where('id', $request?->enrollment_id)->first();
        $order_id = 'order_id_' . time();

        $data = [
            'merchant_id' => env('CCAVENUE_MERCHANT_ID'),
            'order_id' => $order_id,
            'currency' => $request->currency ?? 'INR',
            'amount' => number_format($amount, 2, '.', ''),
            'redirect_url' => env(key: 'CCAVENUE_REDIRECT_URL'),
            'cancel_url' => env('CCAVENUE_CANCEL_URL'),
            'language' => 'EN',
            'billing_name' => $enrollment?->first_name . " " . $enrollment?->last_name,
            'billing_email' => $enrollment?->email,
            'billing_tel' => $enrollment?->phone,
            'merchant_param1' => (string) $request->enrollment_id,
            'merchant_param2' => (string) $request->plan_id,
            'merchant_param3' => (string) $request->quantity,
            'merchant_param4' => (string) $request->schedule_id,
            'merchant_param5' => (string) $request->payment_date,
        ];


        $merchantData = '';
        foreach ($data as $key => $value) {
            $merchantData .= $key . '=' . $value . '&';
        }

        $encryptedData = $this->encryptData($merchantData, env('CCAVENUE_WORKING_KEY'));



        return response()->json([
            'encRequest' => $encryptedData,
            'accessCode' => env('CCAVENUE_ACCESS_CODE'),
            'paymentUrl' => env('CCAVENUE_URL')
        ]);
    }

    //     public function ccAvenueCreatePayment(Request $request)
    // {
    //     // $amount = self::calculateAmount($request);
    //     $amount = 400;

    //     if ($amount <= 0) {
    //         return response()->json(['error' => 'Invalid payment amount'], 400);
    //     }

    //     $enrollment = Enrollment::find($request->enrollment_id);


    //     $order_id = 'order_' . time();

    //     $data = [
    //         'merchant_id'      => env('CCAVENUE_MERCHANT_ID'),
    //         'order_id'         => $order_id,
    //         'currency'         => $request->currency ?? 'INR',
    //         'amount'           => number_format($amount, 2, '.', ''),
    //         'redirect_url'     => env('CCAVENUE_REDIRECT_URL'),
    //         'cancel_url'       => env('CCAVENUE_CANCEL_URL'),
    //         'language'         => 'EN',

    //         'billing_name'     => trim(($enrollment->first_name ?? '') . ' ' . ($enrollment->last_name ?? '')),
    //         'billing_email'    => $enrollment->email ?? '',
    //         'billing_tel'      => $enrollment->phone ?? '',

    //         // ✅ CUSTOM FIELD
    //         'merchant_param1'  => $request->enrollment_id,
    //     ];

    //     // ✅ SAFE encoding
    //     $merchantData = http_build_query($data);

    //     $encryptedData = $this->encryptData(
    //         $merchantData,
    //         env('CCAVENUE_WORKING_KEY')
    //     );

    //     return response()->json([
    //         'encRequest' => $encryptedData,
    //         'accessCode' => env('CCAVENUE_ACCESS_CODE'),
    //         'paymentUrl' => env('CCAVENUE_URL'), // test or live
    //     ]);
    // }


    public function ccAvenueCallback(Request $request)
    {
        $encResponse = $request->input('encResp');

        if (!$encResponse) {
            abort(400, 'CCAvenue response missing');
        }
        $encResponse = str_replace(' ', '+', $encResponse);


        $decrypted = self::decryptData(
            $encResponse,
            env('CCAVENUE_WORKING_KEY')
        );
        parse_str($decrypted, $responseData);
        // REQUIRED FIELDS CHECK
        if (!isset($responseData['order_status'], $responseData['order_id'])) {
            abort(400, 'Invalid CCAvenue payload');
        }

        $orderStatus = $responseData['order_status'];
        $orderId = $responseData['order_id'];
        $amount = $responseData['amount'] ?? null;

        $this->processCcAvenueResponse($responseData);

        if ($responseData['order_status'] === 'Success') {
            // update DB, mark paid

            return redirect(env('FRONTEND_SUCCESS_URL'));
            } else {
                return redirect(env('FRONTEND_FAILURE_URL'));
                }

    }






    // public function response(Request $request)
    // {
    //     $encResp = $request->input('encResp');

    //     $decrypted = Ccavenue::decrypt(
    //         $encResp,
    //         config('ccavenue.working_key')
    //     );

    //     parse_str($decrypted, $responseData);

    //     dd($responseData);

    //     $enrollment = Enrollment :: where('id' , $responseData->merchant_param1)->get();
    //     $plan = PlanMapping::where('id', $responseData->merchant_param2)->first();

    //      $paymentStatus = $responseData->order_status;
    //         $status = match ($paymentStatus) {
    //             'true', '1', true, 1 => 'completed',
    //             'Failure', '0', false, 0 => 'Not Completed',
    //             default => 'Not Completed'
    //         };

    //         $lastInvoice = Enrollment::whereNotNull('invoice_no')
    //             ->where('invoice_no', 'like', 'INV%')
    //             ->latest('id')
    //             ->value('invoice_no');

    //         if ($lastInvoice) {
    //             $nextNumber = ((int) substr($lastInvoice, 3)) + 1;
    //         } else {
    //             $nextNumber = 1;
    //         }

    //         $invoiceNo = 'INV' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

    //             $enrollment->update([
    //             'course_id' => $plain->course_id,
    //             'transaction_id' => $responseData->order_id,
    //             'plan_id' => $plain->plan_id,
    //             'schedule_id' => $responseData->merchant_param3,
    //             'quantity' => $responseData->merchant_param4,
    //             'paid_amount' => $responseData->amount,
    //             'payment_status' => $responseData->order_status,
    //             'payment_date' => $responseData->trans_date,
    //             'invoice_no' => $invoiceNo ,
    //             'currency' => $responseData->bin_country,
    //         ]);




    //     // $order->tracking_id = $responseData['tracking_id'] ?? null;
    //     // $order->payment_response = json_encode($responseData);

    //     // if ($responseData['order_status'] === 'Success') {
    //     //     $order->status = 'success';
    //     //     $order->save();

    //     //     return redirect(config('app.frontend_url') .
    //     //         '/payment/success?order=' . $order->order_number);
    //     // }

    //     // $order->status = 'failed';
    //     // $order->save();

    //     return redirect(config('app.frontend_url') .
    //         '/payment/failure?order=' . $order->order_number);
    // }

    // public function triggerRespose(Request $request)
    // {
    //     // ✅ STATIC CCAvenue SUCCESS Response (Real CCAvenue format)
    //     // ✅ INTEGER transaction_id for INTEGER column
    //     $staticData = [
    //         'order_id' => rand(100000, 999999),              // ✅ INTEGER!
    //         'order_status' => 'Success',
    //         'amount' => '999.00',
    //         'merchant_param5' => '2026-02-03 12:12',
    //         'merchant_param1' => 11,                          // ✅ Existing enrollment ID
    //         'merchant_param2' => 8,
    //         'merchant_param3' => 1,
    //         'merchant_param4' => '0BSLKFW5D019c22b8163f',
    //         'tracking_id' => rand(10000, 99999),
    //         'bank_ref_no' => 'BANK_REF_' . rand(10000, 99999),
    //     ];

    //     // ✅ FAILED Response test (uncomment to test)
    //     // $staticData['order_status'] = 'Failure';
    //     // $staticData['failure_msg'] = 'Payment declined';

    //     \Log::info('CCAvenue Trigger Test', $staticData);

    //     try {
    //         // 🔥 TRIGGER YOUR FUNCTION!
    //         $result = $this->processCcAvenueResponse($staticData);
    //         // dd($result);

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'CCAvenue response triggered successfully!',
    //             'test_data' => $staticData,
    //             'result' => $result,
    //             'enrollment_updated' => Enrollment::find($staticData['merchant_param1'])
    //         ]);

    //     } catch (\Exception $e) {
    //         \Log::error('Trigger failed: ' . $e->getMessage());
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ], 500);
    //     }
    // }


    private function processCcAvenueResponse(array $data)
    {
        // if (!isset($data['order_id'], $data['order_status'])) {
        //     abort(400, 'Malformed CCAvenue response');
        // }

        Log::info("start saving payment : ".$data['order_status'] );
        Log::info("start saving payment : ".$data['merchant_param5'] );

        $enrollment = Enrollment::find($data['merchant_param1'] ?? null);

        if (!$enrollment) {
            abort(404, 'Enrollment not found');
        }

        // // 🔐 prevent duplicate processing
        // if ($enrollment->payment_status === 'completed') {
        //     return redirect(
        //         config('app.frontend_url') .
        //         '/payment/success?order=' . $data['order_id']
        //     );
        // }

        $status = $data['order_status'] ;
        // $status = 'completed';

        try {
            $paymentDate = !empty($data['merchant_param5'])
                ? Carbon::parse($data['merchant_param5'])
                : now();
        } catch (\Exception $e) {
            Log::error('Invalid payment_date from CCAvenue', [
                'value' => $data['merchant_param5'] ?? null,
            ]);
            $paymentDate = now();
        }

        $mapping = PlanMapping ::find( $data['merchant_param2']);


        \DB::transaction(function () use ($data, $enrollment,$paymentDate ,$mapping, $status) {

            $lastInvoice = Enrollment::whereNotNull('invoice_no')
            ->where('invoice_no', 'like', 'INV%')
            ->latest('id')
            ->value('invoice_no');

        $nextNumber = $lastInvoice
            ? ((int) substr($lastInvoice, 3)) + 1
            : 1;

        $invoiceNo = 'INV' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $enrollment->update([
                'transaction_id' => $data['order_id'],
                'plan_id'        => $data['merchant_param2'] ?? null,
                'quantity'       => $data['merchant_param3'] ?? 1,
                'schedule_id'    => $data['merchant_param4'] ?? null,
                'paid_amount'    => $data['amount'] ?? 0,
                'currency'       => $data['currency'] ?? 'INR',
                'payment_status' => $status,
                // 'payment_date' => !empty($data['merchant_param5'])
                //                     ? Carbon::parse($data['merchant_param5'])->format('Y-m-d H:i:s')
                //                     : now(),
                'payment_date'   => $paymentDate->format('Y-m-d H:i:s'),
                'course_id' => $mapping->course_id,
                'plan_id' => $mapping->plan_id,
                'invoice_no'     => $invoiceNo,
            ]);
        });


            $plan = Plan :: find($mapping->plan_id);
            $schedule = CourseSchedule ::where('course_schedule_id' , $enrollment->schedule_id )->first();
            $schedule_end_date = $schedule->class_end_date ?? '_';
            $schedule_start_date = $schedule->class_start_date ?? '_';
            $plan_name       = $plan->name ?? '-';
            $course = Course ::find($schedule->course_id);


        if ($status === 'Success') {
            $apiController = new ApiController();
            $invoice = $apiController->generateInvoiceByEnrollmentId($enrollment->id);
            MailHelper::sendSuccessPaymentMail(
                $enrollment->email,
                trim($enrollment->first_name . ' ' . $enrollment->last_name),
                $course->course_title,
                $enrollment->transaction_id,
                $enrollment->paid_amount,
                $enrollment->payment_date,
                $status,
                $enrollment->currency,
                $schedule_start_date ,
                $schedule_end_date,
                $plan_name ,
                $invoice['url'],
                'payment checkout'
            );


            Log::info('Payment success mail sent successfully.', [
                'email' => $enrollment->email,
                'transaction_id' => $enrollment->transaction_id,
            ]);


            // return redirect(
            //     config('app.frontend_url') .
            //     '/payment/success?order=' . $data['order_id']
            // );
        }

        // return redirect(
        //     config('app.frontend_url') .
        //     '/payment/failure?order=' . $data['order_id']
        // );
    }








    // public function decypher(Request $request){
    // $data =  $request->encData;
    //   $key = pack('H*', md5('C60929E728F2B6058C49AFE7C50D1BCA'));
    //     $iv = pack('C*', ...range(0, 15));
    //     $encryptedText = hex2bin($data);
    //     $decrypt =  openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

    //     dd($decrypt);

    // }

























    /**
     * ==============================================================================
     * Helper Function
     * ==============================================================================
     */




    public static function calculateAmount($request)
    {
        try {
            if (!$request->filled('plan_id') || !$request->filled('quantity')) {
                throw new Exception('Invalid request data.');
            }

            $plan = PlanMapping::find($request->plan_id);
            if (!$plan) {
                throw new Exception('Plan not found.');
            }

            $multiplier = CourseCountry::where('currency', $request->currency)
                ->value('value_in_dollar') ?? 1;

            $courseFee = (float) $plan->course_fee;
            $discount = $plan->discount ?? 0;
            $tax = $plan->tax ?? 0;
            $qty = (int) $request->quantity;

            $discountedFee = $courseFee * (1 - ($discount / 100));
            $taxedFee = $discountedFee * (1 + ($tax / 100));

            if ($request->filled('promo_code')) {
                $promo = PromoCode::find($request->promo_code);
                if ($promo && $promo->discount_percentage) {
                    $taxedFee *= (1 - ($promo->discount_percentage / 100));
                }
            }

            $amount = $taxedFee * $qty * $multiplier;

            return round($amount, 2);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return 0;
        }
    }


    private function encryptData($plainText, $key)
    {
        $key = pack('H*', md5($key));
        $iv = pack('C*', ...range(0, 15));
        $encrypted = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return bin2hex($encrypted);
    }

    private function decryptData($encryptedText, $key)
    {
        $key = pack('H*', md5($key));
        $iv = pack('C*', ...range(0, 15));
        $encryptedText = hex2bin($encryptedText);
        return openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }



    /**
     * ========================
     * Va;idate Payment
     * ========================
     */

    public function validPayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'enrollment_id' => 'required|integer',
                'plan_id' => 'nullable|integer|exists:tbl_plan_mappings,id',
                'payment_id' => 'required|string|max:255',
                'currency' => 'required',
                'quantity' => 'required',
                'payment_status' => 'required|in:true,false,1,0',
                'payment_date' => 'required',
                'promo_code' => 'required',
                'schedule_id' => 'nullable'
            ]);


            $enrollment = Enrollment::where('id', $validated['enrollment_id'])->first();
            $plain = PlanMapping::where('id', $validated['plan_id'])->first();
            // $schedule = CourseSchedule::where('course_id', $plan->course_id)
            //                             ->where('plan_id', $plan->plan_id)
            //                             ->first();

            // dd($course);
            if (!$enrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Enrollment not found',
                ], 404);
            }
            $paymentStatus = $validated['payment_status'];
            $status = match ($paymentStatus) {
                'true', '1', true, 1 => 'completed',
                'false', '0', false, 0 => 'Not Completed',
                default => 'Not Completed'
            };

            $lastInvoice = Enrollment::whereNotNull('invoice_no')
                ->where('invoice_no', 'like', 'INV%')
                ->latest('id')
                ->value('invoice_no');

            if ($lastInvoice) {
                $nextNumber = ((int) substr($lastInvoice, 3)) + 1;
            } else {
                $nextNumber = 1;
            }

            $invoiceNo = 'INV' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);



            $amountrequest = new Request([
                'currency' => $validated['currency'],
                'plan_id' => $validated['plan_id'],
                'quantity' => $validated['quantity'],
                'promo_code' => $validated['promo_code'],
            ]);

            // dd($amountrequest);
            $amount = self::calculateAmount((object) $amountrequest);


            $enrollment->update([
                'course_id' => $plain->course_id,
                'transaction_id' => $validated['payment_id'],
                'plan_id' => $plain->plan_id,
                'schedule_id' => $validated['schedule_id'],
                'quantity' => $validated['quantity'],
                'paid_amount' => $amount,
                'payment_status' => $status,
                'payment_date' => $validated['payment_date'] ?? null,
                'invoice_no' => $invoiceNo ,
                'currency' => $validated['currency']
            ]);

            // dd($enrollment->payment_status );
             $plan = Plan ::find( $enrollment->plan_id);
            $schedule = CourseSchedule ::where('course_schedule_id' , $enrollment->schedule_id )->first();
            $schedule_end_date = $schedule->class_end_date ?? '_';
            $schedule_start_date = $schedule->class_start_date ?? '_';
            $plan_name       = $plan->name ?? '-';

             $invoiceNo = $enrollment->invoice_no;
            $fileName = 'invoice_' . $invoiceNo . '.pdf';
            $filePath = public_path('invoices');
            $invoiceurl = asset('invoices/' . $fileName);

            if ($enrollment->payment_status == 'completed') {
                $fullName = trim($enrollment->first_name . ' ' . $enrollment->last_name);

                MailHelper::sendSuccessPaymentMail(
                    $enrollment->email,
                    $fullName,
                    $enrollment?->Coursedate?->course_title,
                    $enrollment?->transaction_id,
                    $amount,
                    $enrollment?->payment_date,
                    $enrollment?->payment_status,
                    $enrollment?->currency,
                    $schedule_start_date,
                    $schedule_end_date,
                    $plan_name ,
                    $invoiceurl,
                    'payment checkout'
                );

            }

            return response()->json([
                'success' => true,
                'message' => 'Enrollment updated successfully',
                'data' => $enrollment,
            ], 200);

        } catch (Exception $e) {
            Log::error('Enrollment update failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
                'err' => $e->getMessage()
            ], 500);
        }
    }







}

