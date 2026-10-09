<?php

namespace Modules\DynamicMaster\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\DynamicMaster\Services\BankService;
use Modules\DynamicMaster\Http\Requests\BankRequest;
use Illuminate\Http\Request;

class BankController extends Controller
{
    protected BankService $service;

    public function __construct(BankService $service)
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

    protected function store(BankRequest $request)
    {
        return $this->service->store($request);
    }

    protected function edit(Request $request)
    {
       
        $id= $request->id;
        return $this->service->edit($id);
    }



}
