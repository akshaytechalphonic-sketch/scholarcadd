<?php

namespace Modules\Enrolled\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Enrolled\Services\EnrolledCorporateService;
use Illuminate\Http\Request;

class EnrolledCorporateController extends Controller
{
    protected EnrolledCorporateService $service;

    public function __construct(EnrolledCorporateService $service)
    {
        $this->service = $service;
    }

    protected function Corporateindex(Request $request)
    {
        return $this->service->Corporateindex($request);
    }
    protected function enrolledCorporateStore(Request $request)
    {
        return $this->service->enrolledCorporateStore($request);
    }
    protected function CorporatenewDataindex(Request $request)
    {
        return $this->service->CorporateNewindex($request);
    }

}
