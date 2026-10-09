<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\EmployeeService;
use Modules\DynamicMaster\Http\Requests\EmployeeRequest;
use Modules\DynamicMaster\Http\Requests\CreateUserRequest;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected EmployeeService $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    protected function index(Request $request)
    {
        return $this->service->all($request);
    }

    protected function create(Request $request)
    {        
        return $this->service->create($request);
    }

    protected function store(EmployeeRequest $request)
    {
        return $this->service->store($request);
    }

    protected function edit(Request $request)
    {
        return $this->service->edit($request);
    }

    protected function assign_employee(Request $request)
    {
       return $this->service->assign_employee($request);
    }

    protected function create_user(CreateUserRequest $request)
    {
        return $this->service->create_user($request);
    }

}
