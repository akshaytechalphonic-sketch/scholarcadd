<?php

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Workflow\Services\WorkflowAssignmentService;
use Illuminate\Http\Request;

class WorkflowAssignmentController extends Controller
{
    protected WorkflowAssignmentService $service;

    public function __construct(WorkflowAssignmentService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function add(Request $request)
    {
        return $this->service->add($request);
    }

    protected function show(Request $request,$id)
    {
        return $this->service->show($id);
    }

    protected function edit(Request $request)
    {
        return $this->service->edit($request);
    }


}
