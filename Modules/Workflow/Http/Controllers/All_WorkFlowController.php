<?php

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Workflow\Services\All_WorkFlowService;
use Illuminate\Http\Request;

class All_WorkFlowController extends Controller
{
    protected All_WorkFlowService $service;

    public function __construct(All_WorkFlowService $service)
    {
        $this->service = $service;
    }

    protected function setup_list(Request $request)
    {
        return $this->service->setup_list($request);
    }

    protected function show_setup(Request $request,$id)
    {
        return $this->service->show_setup($id);
    }

     protected function edit_setup(Request $request)
    {
        return $this->service->edit_setup($request);
    }

    protected function add_setup(Request $request)
    {
        return $this->service->add_setup($request);
    }

    //steps


    protected function step_list(Request $request)
    {
        return $this->service->step_list($request);
    }

    protected function show_step(Request $request,$id)
    {
        return $this->service->show_step($id);
    }

    protected function edit_step(Request $request)
    {
        return $this->service->edit_step($request);
    }

    protected function add_step(Request $request)
    {
        return $this->service->add_step($request);
    }

    protected function save_conditional_steps(Request $request)
    {
        return $this->service->save_conditional_steps($request);
    }

    protected function workflow_steps_get_next(Request $request,$id)
    {        
        return $this->service->workflow_steps_get_next($id);
    }

    protected function workflow_steps_store_next(Request $request)
    {
        return $this->service->workflow_steps_store_next($request);
    }

    protected function wf_setup_validation(Request $request)
    {
        return $this->service->wf_setup_validation($request);
    }



}
