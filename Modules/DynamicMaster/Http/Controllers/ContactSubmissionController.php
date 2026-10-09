<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\DynamicMaster\Models\ContactSubmission;
use Modules\DynamicMaster\Services\SubmissionService;


class ContactSubmissionController extends Controller
{
    protected SubmissionService $service;

    public function __construct(SubmissionService $service)
    {
        $this->service = $service;
    }

    protected function SubmissionList(Request $request)
    {

        return $this->service->SubmissionList($request);

    }
    protected function Submissionview(Request $request)
    {
        return $this->service->Submissionview($request);
    }
    protected function submissionContactStatus (Request $request)
    {
        return $this->service->submissionContactStatus($request);
    }
}

