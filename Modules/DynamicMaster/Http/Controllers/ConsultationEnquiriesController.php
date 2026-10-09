<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\DynamicMaster\Models\ConsultationEnquiry;
use Modules\DynamicMaster\Services\ConsultationEnquiryService;

class ConsultationEnquiriesController extends Controller
{
    protected ConsultationEnquiryService $service;

    public function __construct(ConsultationEnquiryService $service)
    {
        $this->service = $service;
    }

    protected function EnquiriesList(Request $request)
    {

        return $this->service->EnquiriesList($request);

    }
    protected function Enquiriesview(Request $request)
    {
        return $this->service->Enquiriesview($request);
    }
    protected function EnquiriesContactStatus (Request $request)
    {
        return $this->service->EnquiriesContactStatus($request);
    }
    
}


