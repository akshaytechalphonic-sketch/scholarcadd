<?php

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Workflow\Services\WorkflowRequestService;
use Illuminate\Http\Request;

class WorkflowRequestController extends Controller
{ 
    protected WorkflowRequestService $service;

    public function __construct(WorkflowRequestService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function add(Request $request)
    {
        return $this->service->create($request);
    }

    protected function store(Request $request)
    {
        return $this->service->store($request);
    }
    protected function submitApproveRejModal(Request $request)
    {
        return $this->service->submitApproveRejModal($request);
    }
    
  

}
