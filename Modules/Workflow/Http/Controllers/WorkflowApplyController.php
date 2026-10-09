<?php

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Workflow\Services\WorkflowApplyService;
use Illuminate\Http\Request;

class WorkflowApplyController extends Controller
{ 
    protected WorkflowApplyService $service;

    public function __construct(WorkflowApplyService $service)
    {
        $this->service = $service;
    }
  
    protected function apply_workflow(Request $request)
    {
        return $this->service->apply_workflow($request);
    }
 

}
