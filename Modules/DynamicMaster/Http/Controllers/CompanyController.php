<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\CompanyService;
use Modules\DynamicMaster\Http\Requests\CompanyRequest;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected CompanyService $service;

    public function __construct(CompanyService $service)
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

    protected function store(CompanyRequest $request)
    {
        return $this->service->store($request);
    }

     protected function edit(Request $request)
    {
        return $this->service->edit($request);
    }



}
