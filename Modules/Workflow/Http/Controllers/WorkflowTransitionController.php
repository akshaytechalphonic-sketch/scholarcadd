<?php

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Workflow\Services\WorkflowTransitionService;
use Illuminate\Http\Request;

class WorkflowTransitionController extends Controller
{ 
    protected WorkflowTransitionService $service;

    public function __construct(WorkflowTransitionService $service)
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
    protected function all_transition(Request $request)
    {
        return $this->service->all_transition($request);
    }
    protected function indexLines(Request $request)
    {
        return $this->service->indexLines($request);
    }
    protected function showEmployeeDelegation(Request $request,$id)
    {
        return $this->service->showEmployeeDelegation($request,$id);
    }
    protected function updateEmployeeDelegation(Request $request)
    {
        return $this->service->updateEmployeeDelegation($request);
    }
    
    
    
 

}
