<?php

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Workflow\Services\WorkflowDeligationService;
use Illuminate\Http\Request;

class WorkflowDeligationController extends Controller
{
    protected WorkflowDeligationService $service;

    public function __construct(WorkflowDeligationService $service)
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

    protected function markInactive(Request $request,$id)
    {
        return $this->service->markInactive($id);
    }

    protected function edit(Request $request)
    {
        return $this->service->edit($request);
    }


}
