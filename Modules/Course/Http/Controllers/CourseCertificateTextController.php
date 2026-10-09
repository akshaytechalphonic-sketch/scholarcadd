<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\CourseCertificateTextService;

class CourseCertificateTextController extends Controller
{
    protected CourseCertificateTextService $service;

    public function __construct(CourseCertificateTextService $service)
    {
        $this->service = $service;
    }

    protected function CourseCertificateTextList(Request $request)
    {
        return $this->service->CourseCertificateTextList($request);

    }

     protected function CourseCertificateTextCreate($id = null)
    {
        return $this->service->CourseCertificateTextCreate($id);
    }

    public function CourseCertificateTextStore(Request $request)
    {
        $result = $this->service->CourseCertificateTextStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-certificate-text-list')->with('success', $result['message']);
    }

    protected function CourseCertficateEdit(Request $request)
    {
        return $this->service->CourseCertificateTextCreate($request->id);
    }

    protected function CourseCertificateUpdate(Request $request)
    {
       $result = $this->service->CourseCertificateTextStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-certificate-text-list')->with('success', $result['message']);
    }

    public function getStates(Request $request)
    {
        $countryId = $request->country_id;
        $states = $this->service->getStatesByCountry($countryId);
        return response()->json($states);
    }


}
