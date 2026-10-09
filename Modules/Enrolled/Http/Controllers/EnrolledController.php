<?php

namespace Modules\Enrolled\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Enrolled\Services\EnrolledService;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EnrolledController extends Controller
{
    protected EnrolledService $service;

    public function __construct(EnrolledService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->index($request);
    }
    protected function enrolledStore(Request $request)
    {
        return $this->service->enrolledStore($request);
    }

    protected function CompletedEnrollmentList(Request $request)
    {
        return $this->service->CompletedEnrollmentList($request);
    }
    protected function CompletedEnrollmentView(Request $request)
    {
        return $this->service->CompletedEnrollmentView($request);
    }

    public function downloadInvoicePDF(Request $request)
    {
        try {
            $encryptedId = $request->input('enrollment_id');

            if (!$encryptedId) {
                return abort(400, 'Enrollment ID is required.');
            }

            try {
                $id = decrypt($encryptedId);
            } catch (\Exception $e) {
                return abort(400, 'Invalid Enrollment ID.');
            }

            $enrollment = Enrollment::findOrFail($id);

            if (empty($enrollment->invoice_no)) {
                return abort(404, 'Invoice number not generated yet.');
            }

            $invoiceNo = $enrollment->invoice_no;
            $fileName = 'invoice_' . $invoiceNo . '.pdf';
            $filePath = public_path('invoices/' . $fileName);


            if (!File::exists($filePath)) {
                return abort(404, 'Invoice file not found.');
            }

            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/pdf',
            ]);

        } catch (ModelNotFoundException $e) {
            return abort(404, 'Enrollment not found.');

        } catch (\Exception $e) {
            // Optional: log error for debugging
            \Log::error('Invoice Download Error: ' . $e->getMessage());

            return abort(500, 'Something went wrong while downloading invoice.');
        }
    }


}
