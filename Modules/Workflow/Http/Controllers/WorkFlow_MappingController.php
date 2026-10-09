<?php

namespace Modules\Workflow\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Workflow\Services\WorkFlow_MappingService;
use Illuminate\Http\Request;

class WorkFlow_MappingController extends Controller
{ 
    protected WorkFlow_MappingService $service;

    public function __construct(WorkFlow_MappingService $service)
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
    protected function mapWorkflow(Request $request)
    {
        return $this->service->mapWorkflow($request);
    }
 

}
