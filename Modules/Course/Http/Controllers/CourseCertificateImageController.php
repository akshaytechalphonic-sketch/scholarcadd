<?php

namespace Modules\Course\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Course\Services\CourseCertificateImageService;

class CourseCertificateImageController extends Controller
{
    protected CourseCertificateImageService $service;

    public function __construct(CourseCertificateImageService $service)
    {
        $this->service = $service;
    }

    protected function CourseCertificateImageList(Request $request)
    {
        return $this->service->CourseCertificateImageList($request);

    }

    protected function CourseCertificateImageCreate($id = null)
    {
        return $this->service->CourseCertificateImageCreate($id);
    }

    public function CourseCertificateImageStore(Request $request)
    {
        $result = $this->service->CourseCertificateImageStore($request);
        if (!$result['status']) {
            return back()->with('error', $result['message'])->withInput();
        }
        return redirect()->route('course-certificate-image-list')->with('success', $result['message']);
    }

    protected function CourseCertficateImageEdit(Request $request)
    {
        return $this->service->CourseCertificateImageCreate($request->id);
    }

    protected function CourseCertificateImageUpdate(Request $request)
    {
       $result = $this->service->CourseCertificateImageStore($request);
     if (!$result['status']) {
        return back()->with('error', $result['message'])->withInput();
     }
     return redirect()->route('course-certificate-text-list')->with('success', $result['message']);
    }
}
